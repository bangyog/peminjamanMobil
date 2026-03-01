<?php

namespace App\Http\Controllers;

use App\Models\LoanRequest;
use App\Models\LoanRequestAttachment;
use App\Models\LoanStatusLog;
use App\Models\VehicleReturn;
use App\Models\Unit;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ReturnExpense;
use App\Models\ReturnAttachment;
use App\Models\User;
use App\Notifications\LoanNotification; // ✅ Tetap dipakai

class LoanRequestController extends Controller
{
    // ============================================================
    // 🔔 HELPER — Kirim notifikasi (silent fail)
    // ============================================================
    private function sendNotification(User $user, string $title, string $message, string $url, string $type = 'info', ?string $reason = null): void
    {
        try {
            $user->notify(new LoanNotification(
                title:   $title,
                message: $message,
                url:     $url,
                type:    $type,
                reason:  $reason,
            ));
        } catch (\Exception $e) {
            Log::warning('Gagal kirim notifikasi', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);
        }
    }

    // ============================================================
    // INDEX — Daftar pengajuan milik user yang login
    // ============================================================
    public function index(Request $request)
    {
        $query = LoanRequest::where('requester_id', Auth::id())
            ->with(['unit', 'vehicle', 'approvals', 'assignment.assignedVehicle']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('destination', 'like', '%' . $request->search . '%')
                    ->orWhere('purpose', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $base = LoanRequest::where('requester_id', Auth::id());
        $stats = [
            'pending'   => (clone $base)->where('status', 'submitted')->count(),
            'approved'  => (clone $base)->whereIn('status', ['approved_kepala', 'approved_ga', 'assigned'])->count(),
            'in_use'    => (clone $base)->where('status', 'in_use')->count(),
            'completed' => (clone $base)->where('status', 'returned')->count(),
            'rejected'  => (clone $base)->where('status', 'rejected')->count(),
        ];

        $requests = $query->latest()->paginate(10)->withQueryString();

        return view('loan-requests.index', compact('requests', 'stats'));
    }

    // ============================================================
    // CREATE — Form pengajuan baru
    // ============================================================
    public function create()
    {
        $user     = Auth::user();
        $units    = Unit::where('is_active', 1)->orderBy('name')->get();
        $vehicles = Vehicle::where('status', 'available')->orderBy('brand')->get();

        return view('loan-requests.create', compact('units', 'vehicles', 'user'));
    }

    // ============================================================
    // STORE — Simpan pengajuan baru
    // ============================================================
    public function store(Request $request)
    {
        $request->validate([
            'unit_id'                => 'required|exists:units,id',
            'purpose'                => 'required|string|max:255',
            'destination'            => 'nullable|string|max:255',
            'projek'                 => 'nullable|string|max:255', 
            'anggaran_awal'          => 'nullable|string|max:255', 
            'siap_di'                => 'nullable|string|max:255',
            'kembali_di'             => 'nullable|string|max:255',
            'request_city'           => 'nullable|string|max:100',
            'preferred_vehicle_id'   => 'nullable|exists:vehicles,id',
            'requested_vehicle_text' => 'nullable|string|max:255',
            'depart_at'              => 'required|date',
            'expected_return_at'     => 'required|date|after:depart_at',
            'notes'                  => 'nullable|string|max:1000',
            'requester_signature'    => 'nullable|string',
            'attachments.*'          => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

        DB::beginTransaction();
        try {
            $signaturePath = null;
            if (
                $request->requester_signature
                && str_starts_with($request->requester_signature, 'data:image')
            ) {
                $signaturePath = $this->saveSignature(
                    $request->requester_signature,
                    'requester_signature',
                    Auth::id()
                );
            }

            $loanRequest = LoanRequest::create([
                'requester_id'           => Auth::id(),
                'unit_id'                => $request->unit_id,
                'preferred_vehicle_id'   => $request->preferred_vehicle_id,
                'request_city'           => $request->request_city,
                'purpose'                => $request->purpose,
                'destination'            => $request->destination,
                'projek'                 => $request->projek,
                'anggaran_awal'          => $request->anggaran_awal, 
                'siap_di'                => $request->siap_di,
                'kembali_di'             => $request->kembali_di,
                'requested_vehicle_text' => $request->requested_vehicle_text,
                'notes'                  => $request->notes,
                'requester_signature'    => $signaturePath,
                'depart_at'              => $request->depart_at,
                'expected_return_at'     => $request->expected_return_at,
                'status'                 => 'submitted',
            ]);

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('loan-attachments', 'public');
                    LoanRequestAttachment::create([
                        'loan_request_id' => $loanRequest->id,
                        'file_name'       => $file->getClientOriginalName(),
                        'file_url'        => $path,
                        'mime_type'       => $file->getMimeType(),
                        'file_size_bytes' => $file->getSize(),
                        'uploaded_by'     => Auth::id(),
                    ]);
                }
            }

            LoanStatusLog::create([
                'loan_request_id' => $loanRequest->id,
                'from_status'     => null,
                'to_status'       => 'submitted',
                'changed_by'      => Auth::id(),
                'change_note'     => 'Pengajuan peminjaman kendaraan dibuat',
            ]);

            DB::commit();

            // ✅ Notifikasi — cek apakah unit ini adalah unit Akuntansi
            $isAkuntansiUnit = User::where('unit_id', $loanRequest->unit_id)
                ->where('role', 'admin_akuntansi')
                ->exists();

            if ($isAkuntansiUnit) {
                // ✅ Unit Akuntansi → notify Admin Akuntansi
                $adminAkuntansiList = User::where('unit_id', $loanRequest->unit_id)
                    ->where('role', 'admin_akuntansi')
                    ->get();
                foreach ($adminAkuntansiList as $admin) {
                    $this->sendNotification(
                        $admin,
                        'Pengajuan Baru Menunggu Persetujuan',
                        'Pengajuan #' . $loanRequest->id . ' dari ' . Auth::user()->full_name . ' ke ' . ($loanRequest->destination ?? '-') . ' menunggu persetujuan kamu.',
                        route('approvals.akuntansi.index'),
                        'info'
                    );
                }
            } else {
                // ✅ Unit lain → notify Kepala Departemen unit tersebut
                $kepalaList = User::where('unit_id', $loanRequest->unit_id)
                    ->where('role', 'kepala_departemen')
                    ->get();
                foreach ($kepalaList as $kepala) {
                    $this->sendNotification(
                        $kepala,
                        'Pengajuan Baru Menunggu Persetujuan',
                        'Pengajuan #' . $loanRequest->id . ' dari ' . Auth::user()->full_name . ' ke ' . ($loanRequest->destination ?? '-') . ' menunggu persetujuan kamu.',
                        route('approvals.kepala.index'),
                        'info'
                    );
                }
            }

            return redirect()->route('loan-requests.show', $loanRequest)
                ->with('success', 'Pengajuan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating loan request: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    // ============================================================
    // SHOW — Detail pengajuan
    // ============================================================
    public function show(LoanRequest $loanRequest)
    {
        $loanRequest->load([
            'requester',
            'unit',
            'vehicle',
            'approvals.approver',
            'assignment.assignedVehicle',
            'assignment.assignedBy',
            'attachments',
        ]);

        return view('loan-requests.show', compact('loanRequest'));
    }

    // ============================================================
    // EDIT — Form edit pengajuan
    // ============================================================
    public function edit(LoanRequest $loanRequest)
    {
        if ($loanRequest->requester_id !== Auth::id()) {
            abort(403);
        }

        if ($loanRequest->status !== 'submitted') {
            return redirect()->route('loan-requests.show', $loanRequest)
                ->with('error', 'Pengajuan ini tidak dapat diedit karena sudah diproses.');
        }

        $units = Unit::where('is_active', 1)->orderBy('name')->get();

        $vehicles = Vehicle::where('status', 'available')
            ->orWhere('id', $loanRequest->preferred_vehicle_id)
            ->orderBy('brand')
            ->get();

        return view('loan-requests.edit', compact('loanRequest', 'units', 'vehicles'));
    }

    // ============================================================
    // UPDATE — Simpan perubahan pengajuan
    // ============================================================
    public function update(Request $request, LoanRequest $loanRequest)
    {
        if ($loanRequest->requester_id !== Auth::id()) {
            abort(403);
        }

        if ($loanRequest->status !== 'submitted') {
            return redirect()->route('loan-requests.show', $loanRequest)
                ->with('error', 'Pengajuan ini tidak dapat diedit.');
        }

        $request->validate([
            'unit_id'                => 'required|exists:units,id',
            'purpose'                => 'required|string|max:255',
            'destination'            => 'nullable|string|max:255',
            'projek'                 => 'nullable|string|max:255',
            'anggaran_awal'          => 'nullable|string|max:255',
            'siap_di'                => 'nullable|string|max:255',
            'kembali_di'             => 'nullable|string|max:255',
            'request_city'           => 'nullable|string|max:100',
            'preferred_vehicle_id'   => 'nullable|exists:vehicles,id',
            'requested_vehicle_text' => 'nullable|string|max:255',
            'depart_at'              => 'required|date',
            'expected_return_at'     => 'required|date|after:depart_at',
            'notes'                  => 'nullable|string|max:1000',
            'requester_signature'    => 'nullable|string',
            'attachments.*'          => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

        DB::beginTransaction();
        try {
            $updateData = [
                'unit_id'                => $request->unit_id,
                'preferred_vehicle_id'   => $request->preferred_vehicle_id,
                'request_city'           => $request->request_city,
                'purpose'                => $request->purpose,
                'destination'            => $request->destination,
                'projek'                 => $request->projek,
                'anggaran_awal'          => $request->anggaran_awal,
                'siap_di'                => $request->siap_di,
                'kembali_di'             => $request->kembali_di,
                'requested_vehicle_text' => $request->requested_vehicle_text,
                'notes'                  => $request->notes,
                'depart_at'              => $request->depart_at,
                'expected_return_at'     => $request->expected_return_at,
            ];

            if (
                $request->requester_signature
                && str_starts_with($request->requester_signature, 'data:image')
            ) {
                if ($loanRequest->requester_signature) {
                    Storage::disk('public')->delete($loanRequest->requester_signature);
                }
                $updateData['requester_signature'] = $this->saveSignature(
                    $request->requester_signature,
                    'requester_signature',
                    Auth::id()
                );
            }

            $loanRequest->update($updateData);

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('loan-attachments', 'public');
                    LoanRequestAttachment::create([
                        'loan_request_id' => $loanRequest->id,
                        'file_name'       => $file->getClientOriginalName(),
                        'file_url'        => $path,
                        'mime_type'       => $file->getMimeType(),
                        'file_size_bytes' => $file->getSize(),
                        'uploaded_by'     => Auth::id(),
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('loan-requests.show', $loanRequest)
                ->with('success', 'Pengajuan berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating loan request: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    // ============================================================
    // DESTROY — Hapus pengajuan
    // ============================================================
    public function destroy(LoanRequest $loanRequest)
    {
        if ($loanRequest->requester_id !== Auth::id()) {
            abort(403);
        }

        if ($loanRequest->status !== 'submitted') {
            return redirect()->route('loan-requests.show', $loanRequest)
                ->with('error', 'Pengajuan ini tidak dapat dihapus karena sudah diproses.');
        }

        DB::beginTransaction();
        try {
            if ($loanRequest->requester_signature) {
                Storage::disk('public')->delete($loanRequest->requester_signature);
            }

            foreach ($loanRequest->attachments as $attachment) {
                Storage::disk('public')->delete($attachment->file_url);
            }

            $loanRequest->delete();

            DB::commit();
            return redirect()->route('loan-requests.index')
                ->with('success', 'Pengajuan berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting loan request: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus.');
        }
    }

    // ============================================================
    // DELETE ATTACHMENT
    // ============================================================
    public function deleteAttachment(LoanRequestAttachment $attachment)
    {
        $loanRequest = $attachment->loanRequest;

        if ($loanRequest->requester_id !== Auth::id()) {
            abort(403);
        }

        if ($loanRequest->status !== 'submitted') {
            return back()->with('error', 'Lampiran tidak dapat dihapus karena pengajuan sudah diproses.');
        }

        Storage::disk('public')->delete($attachment->file_url);
        $attachment->delete();

        return back()->with('success', 'Lampiran berhasil dihapus.');
    }

    // ============================================================
    // SHOW RETURN — Form pengembalian kendaraan
    // ============================================================
    public function showReturn(LoanRequest $loanRequest)
    {
        if ($loanRequest->requester_id !== Auth::id()) {
            abort(403);
        }

        if ($loanRequest->status !== 'in_use') {
            return redirect()->route('loan-requests.show', $loanRequest)
                ->with('error', 'Kendaraan belum atau sudah dikembalikan.');
        }

        $loanRequest->load([
            'requester',
            'unit',
            'assignment.assignedVehicle',
        ]);

        return view('loan-requests.return', compact('loanRequest'));
    }

    // ============================================================
    // SUBMIT RETURN — Proses pengembalian kendaraan
    // ============================================================
    public function submitReturn(Request $request, LoanRequest $loanRequest)
    {
        if ($loanRequest->requester_id !== Auth::id()) {
            abort(403);
        }

        if ($loanRequest->status !== 'in_use') {
            return redirect()->route('loan-requests.show', $loanRequest)
                ->with('error', 'Status tidak valid untuk pengembalian.');
        }

        $request->validate([
            'returned_at'           => 'required|date',
            'odometer_km_end'       => 'nullable|integer|min:0',
            'return_note'           => 'nullable|string|max:1000',
            'expense_type.*'        => 'nullable|in:fuel,toll,parking,repair,other',
            'expense_amount.*'      => 'nullable|numeric|min:0',
            'expense_description.*' => 'nullable|string|max:255',
            'expense_receipts.*'    => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'return_attachments.*'  => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $vehicleReturn = VehicleReturn::create([
                'loan_request_id' => $loanRequest->id,
                'returned_at'     => $request->returned_at,
                'received_by'     => null,
                'odometer_km_end' => $request->odometer_km_end,
                'return_note'     => $request->return_note,
            ]);

            if ($request->expense_type) {
                foreach ($request->expense_type as $i => $type) {
                    if (!$type) continue;

                    $receiptPath = null;
                    if ($request->hasFile("expense_receipts.$i")) {
                        $receiptPath = $request->file("expense_receipts.$i")
                            ->store('receipts', 'public');
                    }

                    ReturnExpense::create([
                        'return_id'   => $vehicleReturn->id,
                        'type'        => $type,
                        'description' => $request->expense_description[$i] ?? null,
                        'amount'      => $request->expense_amount[$i] ?? 0,
                        'receipt_url' => $receiptPath,
                        'created_by'  => Auth::id(),
                    ]);
                }
            }

            if ($request->hasFile('return_attachments')) {
                foreach ($request->file('return_attachments') as $file) {
                    ReturnAttachment::create([
                        'return_id'       => $vehicleReturn->id,
                        'type'            => 'return_proof',
                        'file_name'       => $file->getClientOriginalName(),
                        'file_url'        => $file->store('return-attachments', 'public'),
                        'mime_type'       => $file->getMimeType(),
                        'file_size_bytes' => $file->getSize(),
                        'uploaded_by'     => Auth::id(),
                    ]);
                }
            }

            $loanRequest->update(['status' => 'returned']);

            if ($loanRequest->assignment?->assignedVehicle) {
                $vehicleUpdate = ['status' => 'available'];
                if ($request->odometer_km_end) {
                    $vehicleUpdate['odometer_km'] = $request->odometer_km_end;
                }
                $loanRequest->assignment->assignedVehicle->update($vehicleUpdate);
            }

            LoanStatusLog::create([
                'loan_request_id' => $loanRequest->id,
                'from_status'     => 'in_use',
                'to_status'       => 'returned',
                'changed_by'      => Auth::id(),
                'change_note'     => 'Kendaraan dikembalikan oleh pemohon',
            ]);

            DB::commit();

            // ✅ Notif semua Admin GA bahwa kendaraan sudah dikembalikan
            $adminGAList = User::where('role', 'admin_ga')->get();
            foreach ($adminGAList as $adminGA) {
                $this->sendNotification(
                    $adminGA,
                    'Kendaraan Dikembalikan',
                    'Kendaraan dari pengajuan #' . $loanRequest->id . ' oleh ' . Auth::user()->full_name . ' telah dikembalikan dan menunggu verifikasi.',
                    route('approvals.ga.index'),
                    'info'
                );
            }

            return redirect()->route('loan-requests.show', $loanRequest)
                ->with('success', 'Kendaraan berhasil dikembalikan. Menunggu verifikasi Admin GA.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error submitting return: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

// ============================================================
// PDF — Download formulir PDF
// ============================================================
public function  exportPdf(LoanRequest $loanRequest)
{
    $loanRequest->load([
        'requester',
        'unit',
        'vehicle',        // ✅ Ganti 'vehicle' → 'preferredVehicle' (sesuai FK preferred_vehicle_id)
        'approvals.approver',
        'assignment.assignedVehicle',
        
    ]);

    $pdf = Pdf::loadView('loan-requests.pdf', compact('loanRequest'))
        ->setPaper('a4', 'portrait')
        ->setOptions([
            'defaultFont'          => 'Calibri, sans-serif',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled'      => true,   // ✅ WAJIB: agar DomPDF bisa load file signature dari storage
            'chroot'               => storage_path('app/public'), // ✅ izinkan akses storage lokal
        ]);

    $filename = 'Formulir_Pengajuan_' . $loanRequest->id . '_' . now()->format('Ymd') . '.pdf';

    return $pdf->download($filename);
}


    // ============================================================
    // PRIVATE HELPER — Simpan base64 signature ke storage
    // ============================================================
    private function saveSignature(string $base64, string $prefix, int $userId): string
    {
        $parts    = explode(',', $base64);
        $decoded  = base64_decode($parts[1]);
        $filename = 'signatures/' . $prefix . '_' . $userId . '_' . time() . '.png';

        Storage::disk('public')->put($filename, $decoded);

        return $filename;
    }
}
