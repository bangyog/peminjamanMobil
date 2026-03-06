<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanApproval;
use App\Models\LoanAssignment;
use App\Models\LoanRequest;
use App\Models\LoanStatusLog;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Notifications\LoanNotification;

class LoanRequestController extends Controller
{
    // ============================================================
    // 🔔 HELPER — Kirim notifikasi (silent fail)
    // ============================================================
    private function sendNotification(User $user, string $title, string $message, string $url, string $type = 'info', ?string $reason = null): void
    {
        try {
            $user->notify(new LoanNotification(
                title: $title,
                message: $message,
                url: $url,
                type: $type,
                reason: $reason,
            ));
        } catch (\Exception $e) {
            Log::warning('Gagal kirim notifikasi', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);
        }
    }

    // ============================================================
    // INDEX — Admin GA view
    // ============================================================
    public function index(Request $request)
    {
        /** @var \App\Models\User $admin */
        $admin = Auth::user();

        if (!$admin->isAdminGA()) {
            abort(403, 'Akses ditolak.');
        }

        $query = LoanRequest::with([
            'requester',
            'unit',
            'vehicle',
            'assignment.assignedVehicle',
            'approvals',
        ])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        if ($request->filled('search')) {
            $query->whereHas('requester', function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->search . '%');
            });
        }

        $loanRequests = $query->paginate(15)->withQueryString();
        $units        = \App\Models\Unit::orderBy('name')->get();

        $stats = [
            'all'             => LoanRequest::count(),
            'submitted'       => LoanRequest::where('status', 'submitted')->count(),
            'approved_kepala' => LoanRequest::where('status', 'approved_kepala')->count(),
            'approved_ga'     => LoanRequest::where('status', 'approved_ga')->count(),
            'assigned'        => LoanRequest::where('status', 'assigned')->count(),
            'in_use'          => LoanRequest::where('status', 'in_use')->count(),
            'returned'        => LoanRequest::where('status', 'returned')->count(),
            'rejected'        => LoanRequest::where('status', 'rejected')->count(),
        ];

        return view('admin.loan-requests.index', compact('loanRequests', 'units', 'stats'));
    }

    // ============================================================
    // SHOW — Detail loan request
    // ============================================================
    public function show(LoanRequest $loanRequest)
    {
        $loanRequest->load([
            'requester',
            'unit',
            'assignment',
            'vehicle',
            'assignment.assignedVehicle',
            'attachments',
            'approvals.approver',
            'statusLogs.changedBy',
            'return.expenses',
            'return.attachments',
        ]);

        $availableVehicles = Vehicle::where('status', 'available')
            ->orderBy('brand')
            ->orderBy('model')
            ->get();

        return view('admin.loan-requests.show', compact('loanRequest', 'availableVehicles'));
    }

    // ============================================================
    // APPROVE — Admin GA approve + assign vehicle
    // ============================================================
    public function approve(Request $request, LoanRequest $loanRequest)
    {
        /** @var \App\Models\User $admin */
        $admin = Auth::user();

        if (!$admin->isAdminGA()) {
            abort(403, 'Akses ditolak.');
        }

        if (!in_array($loanRequest->status, ['approved_kepala', 'approved_ga'])) {
            return back()->with('error', 'Pengajuan ini tidak dapat disetujui. Status: ' . $loanRequest->status);
        }

        $validated = $request->validate([
            'assigned_vehicle_id' => 'required|exists:vehicles,id',
            'assigned_driver_name' => $loanRequest->driver
                ? 'required|string|max:150'
                : 'nullable|string|max:150',
            'signature'           => 'required|string',
            'notes'               => 'nullable|string|max:1000',
        ], [
            'assigned_vehicle_id.required' => 'Kendaraan harus dipilih',
            'assigned_vehicle_id.exists'   => 'Kendaraan tidak valid',
            'signature.required'           => 'Tanda tangan harus diisi',
        ]);

        DB::beginTransaction();
        try {
            $vehicle = Vehicle::findOrFail($validated['assigned_vehicle_id']);

            if ($vehicle->status !== 'available') {
                return back()->with('error', 'Kendaraan tidak tersedia. Status: ' . $vehicle->status);
            }

            $signaturePath = $this->saveSignature($request->signature, 'ga_' . $loanRequest->id);

            LoanApproval::create([
                'loan_request_id'    => $loanRequest->id,
                'approver_id'        => $admin->id,
                'approval_level'     => 'ga',
                'decision'           => 'approved',
                'reason'             => $validated['notes'] ?? 'Disetujui oleh Admin GA',
                'approver_signature' => $signaturePath,
                'decided_at'         => now(),
            ]);

            LoanAssignment::create([
                'loan_request_id'     => $loanRequest->id,
                'assigned_vehicle_id' => $validated['assigned_vehicle_id'],
                'assigned_driver_name' => $validated['assigned_driver_name'] ?? 'Self-drive',
                'assigned_by'         => $admin->id,
                'assigned_at'         => now(),
            ]);

            $vehicle->update(['status' => 'in_use']);

            $fromStatus = $loanRequest->status;
            $loanRequest->update(['status' => 'in_use']);

            LoanStatusLog::create([
                'loan_request_id' => $loanRequest->id,
                'from_status'     => $fromStatus,
                'to_status'       => 'in_use',
                'changed_by'      => $admin->id,
                'change_note'     => 'Disetujui & kendaraan langsung ditugaskan oleh Admin GA'
                    . ($validated['notes'] ? ': ' . $validated['notes'] : ''),
            ]);

            DB::commit();

            // ✅ Siapkan info driver untuk notifikasi
            $driverInfo = $loanRequest->driver
                ? 'Driver: ' . ($validated['assigned_driver_name'] ?? '-')
                : 'Self-drive (tanpa driver)';

            // ✅ Notif requester — dengan info kendaraan + driver
            $this->sendNotification(
                $loanRequest->requester,
                'Pengajuan Disetujui & Kendaraan Ditugaskan',
                'Pengajuan #' . $loanRequest->id . ' disetujui Admin GA. '
                    . 'Kendaraan: ' . $vehicle->brand . ' ' . $vehicle->model . ' (' . $vehicle->plate_no . '). '
                    . $driverInfo . '.',
                route('loan-requests.show', $loanRequest),
                'success'
            );


            return redirect()->route('admin.loan-requests.show', $loanRequest)
                ->with('success', 'Pengajuan disetujui dan kendaraan langsung ditugaskan!');
        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($signaturePath)) {
                Storage::disk('public')->delete($signaturePath);
            }
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ============================================================
    // REJECT — Admin GA reject
    // ============================================================
    public function reject(Request $request, LoanRequest $loanRequest)
    {
        /** @var \App\Models\User $admin */
        $admin = Auth::user();

        if (!$admin->isAdminGA()) {
            abort(403, 'Akses ditolak.');
        }

        if (!in_array($loanRequest->status, ['approved_kepala', 'approved_ga'])) {
            return back()->with('error', 'Pengajuan ini tidak dapat ditolak. Status: ' . $loanRequest->status);
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ], [
            'rejection_reason.required' => 'Alasan penolakan harus diisi',
        ]);

        DB::beginTransaction();
        try {
            $fromStatus = $loanRequest->status;

            LoanApproval::create([
                'loan_request_id'    => $loanRequest->id,
                'approver_id'        => $admin->id,
                'approval_level'     => 'ga',
                'decision'           => 'rejected',
                'reason'             => $validated['rejection_reason'],
                'approver_signature' => null,
                'decided_at'         => now(),
            ]);

            $loanRequest->update(['status' => 'rejected']);

            LoanStatusLog::create([
                'loan_request_id' => $loanRequest->id,
                'from_status'     => $fromStatus,
                'to_status'       => 'rejected',
                'changed_by'      => $admin->id,
                'change_note'     => 'Ditolak oleh Admin GA: ' . $validated['rejection_reason'],
            ]);

            DB::commit();

            // ✅ Notif requester
            $this->sendNotification(
                $loanRequest->requester,
                'Pengajuan Ditolak Admin GA',
                'Pengajuan #' . $loanRequest->id . ' kamu ditolak oleh Admin GA.',
                route('loan-requests.show', $loanRequest),
                'danger',
                $validated['rejection_reason']
            );

            return redirect()->route('admin.loan-requests.index')
                ->with('success', 'Pengajuan berhasil ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ============================================================
    // MARK IN USE — Admin GA mark kendaraan sedang digunakan
    // ============================================================
    public function markInUse(Request $request, LoanRequest $loanRequest)
    {
        /** @var \App\Models\User $admin */
        $admin = Auth::user();

        if (!$admin->isAdminGA()) {
            abort(403, 'Akses ditolak.');
        }

        if ($loanRequest->status !== 'assigned') {
            return back()->with('error', 'Pengajuan ini belum dalam status assigned.');
        }

        DB::beginTransaction();
        try {
            $loanRequest->update(['status' => 'in_use']);

            if ($loanRequest->assignment && $loanRequest->assignment->assignedVehicle) {
                $loanRequest->assignment->assignedVehicle->update(['status' => 'in_use']);
            }

            LoanStatusLog::create([
                'loan_request_id' => $loanRequest->id,
                'from_status'     => 'assigned',
                'to_status'       => 'in_use',
                'changed_by'      => $admin->id,
                'change_note'     => 'Kendaraan telah diambil oleh peminjam',
            ]);

            DB::commit();

            // ✅ Notif requester
            $this->sendNotification(
                $loanRequest->requester,
                'Kendaraan Sedang Digunakan',
                'Kendaraan untuk peminjaman #' . $loanRequest->id . ' telah diambil dan sedang digunakan.',
                route('loan-requests.show', $loanRequest),
                'success'
            );

            return back()->with('success', 'Status diubah: Kendaraan Sedang Digunakan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ============================================================
    // CANCEL — Admin GA cancel pengajuan
    // ============================================================
    public function cancel(Request $request, LoanRequest $loanRequest)
    {
        /** @var \App\Models\User $admin */
        $admin = Auth::user();

        if (!$admin->isAdminGA()) {
            abort(403, 'Akses ditolak.');
        }

        if (!in_array($loanRequest->status, ['submitted', 'approved_kepala', 'approved_ga', 'assigned'])) {
            return back()->with('error', 'Pengajuan ini tidak dapat dibatalkan.');
        }

        $validated = $request->validate([
            'cancellation_reason' => 'required|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $fromStatus = $loanRequest->status;

            if ($loanRequest->assignment && $loanRequest->assignment->assignedVehicle) {
                $loanRequest->assignment->assignedVehicle->update(['status' => 'available']);
            }

            $loanRequest->update(['status' => 'rejected']);

            LoanStatusLog::create([
                'loan_request_id' => $loanRequest->id,
                'from_status'     => $fromStatus,
                'to_status'       => 'rejected',
                'changed_by'      => $admin->id,
                'change_note'     => 'Dibatalkan oleh Admin GA: ' . $validated['cancellation_reason'],
            ]);

            DB::commit();

            // ✅ Notif requester
            $this->sendNotification(
                $loanRequest->requester,
                'Pengajuan Dibatalkan Admin GA',
                'Pengajuan #' . $loanRequest->id . ' kamu dibatalkan oleh Admin GA.',
                route('loan-requests.show', $loanRequest),
                'danger',
                $validated['cancellation_reason']
            );

            return redirect()->route('admin.loan-requests.index')
                ->with('success', 'Pengajuan berhasil dibatalkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ============================================================
    // VERIFY RETURN — Admin GA verifikasi pengembalian
    // ============================================================
    public function verifyReturn(Request $request, LoanRequest $loanRequest)
    {
        /** @var \App\Models\User $admin */
        $admin = Auth::user();

        if (!$admin->isAdminGA()) {
            abort(403, 'Akses ditolak.');
        }

        if ($loanRequest->status !== 'returned') {
            return back()->with('error', 'Kendaraan belum dalam status dikembalikan.');
        }

        if (!$loanRequest->return) {
            return back()->with('error', 'Data pengembalian tidak ditemukan.');
        }

        $validated = $request->validate([
            'vehicle_condition'  => 'required|in:good,damaged,needs_maintenance',
            'verification_notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $loanRequest->return->update([
                'received_by' => $admin->id,
                'return_note' => $validated['verification_notes'],
            ]);

            if ($loanRequest->assignment && $loanRequest->assignment->assignedVehicle) {
                $newVehicleStatus = match ($validated['vehicle_condition']) {
                    'good'              => 'available',
                    'damaged'           => 'maintenance',
                    'needs_maintenance' => 'maintenance',
                    default             => 'available',
                };
                $loanRequest->assignment->assignedVehicle->update([
                    'status' => $newVehicleStatus,
                ]);
            }

            LoanStatusLog::create([
                'loan_request_id' => $loanRequest->id,
                'from_status'     => 'returned',
                'to_status'       => 'returned',
                'changed_by'      => $admin->id,
                'change_note'     => 'Pengembalian diverifikasi Admin GA. Kondisi: ' . $validated['vehicle_condition'],
            ]);

            DB::commit();

            // ✅ Notif requester
            $this->sendNotification(
                $loanRequest->requester,
                'Pengembalian Diverifikasi Admin GA',
                'Pengembalian kendaraan untuk peminjaman #' . $loanRequest->id . ' telah diverifikasi. Kondisi: ' . $validated['vehicle_condition'] . '.',
                route('loan-requests.show', $loanRequest),
                'success'
            );

            return back()->with('success', 'Pengembalian berhasil diverifikasi!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ============================================================
    // PRIVATE HELPER — Simpan signature
    // ============================================================
    private function saveSignature(string $base64Signature, string $prefix): string
    {
        $image     = str_replace('data:image/png;base64,', '', $base64Signature);
        $image     = str_replace(' ', '+', $image);
        $imageData = base64_decode($image);

        $filename = $prefix . '_signature_' . time() . '_' . uniqid() . '.png';
        $path     = 'signatures/' . $filename;

        Storage::disk('public')->put($path, $imageData);

        return $path;
    }
}
