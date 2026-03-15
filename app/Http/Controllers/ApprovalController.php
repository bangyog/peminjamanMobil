<?php

namespace App\Http\Controllers;

use App\Models\LoanApproval;
use App\Models\LoanRequest;
use App\Models\LoanStatusLog;
use App\Models\User;
use App\Notifications\LoanNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ApprovalController extends Controller
{
    // =========================================================
    // 🔑 HELPER — Simpan signature base64 ke storage
    // =========================================================
    private function saveSignature(string $base64Signature, string $prefix): string
    {
        $image     = str_replace('data:image/png;base64,', '', $base64Signature);
        $image     = str_replace(' ', '+', $image);
        $imageData = base64_decode($image);

        $filename = $prefix . '_' . time() . '.png';
        $path     = 'signatures/' . $filename;

        Storage::disk('public')->put($path, $imageData);

        return $path;
    }

    // =========================================================
    // 🔔 HELPER — Kirim notifikasi (silent fail)
    // =========================================================
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

    // =========================================================
    // 👔 KEPALA DEPARTEMEN — INDEX
    // =========================================================
    public function indexKepala()
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->isKepalaDepartemen()) {
            abort(403, 'Akses ditolak.');
        }

        $pendingRequests = LoanRequest::with([
            'requester',
            'requester.unit',
            'vehicle',
            'attachments',
        ])
            ->where('unit_id', $user->unit_id)
            ->where('status', 'submitted')
            ->latest()
            ->get();

        return view('approvals.kepala.index', compact('pendingRequests'));
    }

    // =========================================================
    // 👔 KEPALA DEPARTEMEN — FORM APPROVE
    // =========================================================
    public function showApproveForm(LoanRequest $loanRequest)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->isKepalaDepartemen()) {
            abort(403, 'Akses ditolak.');
        }

        if ($loanRequest->unit_id !== $user->unit_id) {
            abort(403, 'Anda tidak bisa approve request dari unit lain.');
        }

        if ($loanRequest->status !== 'submitted') {
            return redirect()->route('approvals.kepala.index')
                ->with('error', 'Pengajuan tidak bisa diproses. Status: ' . $loanRequest->status);
        }

        $loanRequest->load([
            'requester',
            'requester.unit',
            'vehicle',
            'attachments',
            'approvals.approver',
        ]);

        return view('approvals.kepala.approve', compact('loanRequest'));
    }

    // =========================================================
    // 👔 KEPALA DEPARTEMEN — APPROVE
    // =========================================================
    public function approveKepala(Request $request, LoanRequest $loanRequest)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->isKepalaDepartemen()) abort(403, 'Akses ditolak.');
        if ($loanRequest->unit_id !== $user->unit_id) abort(403, 'Anda tidak bisa approve request dari unit lain.');
        if ($loanRequest->status !== 'submitted') {
            return back()->with('error', 'Pengajuan tidak bisa diapprove. Status: ' . $loanRequest->status);
        }

        $request->validate(['signature' => 'required|string']);

        $signaturePath = null;
        try {
            DB::beginTransaction();

            if ($request->filled('signature') && str_starts_with($request->signature, 'data:image')) {
                $signaturePath = $this->saveSignature(
                    $request->signature,
                    'approver_' . $loanRequest->id . '_' . $user->id
                );
            }

            LoanApproval::create([
                'loan_request_id'    => $loanRequest->id,
                'approver_id'        => $user->id,
                'approval_level'     => 'kepala',
                'decision'           => 'approved',
                'reason'             => null,
                'approver_signature' => $signaturePath,
                'decided_at'         => now(),
            ]);

            $loanRequest->forceFill([
                'status'           => 'approved_kepala',
                'kepala_signature' => $signaturePath,
            ])->save();

            LoanStatusLog::create([
                'loan_request_id' => $loanRequest->id,
                'from_status'     => 'submitted',
                'to_status'       => 'approved_kepala',
                'changed_by'      => $user->id,
                'change_note'     => 'Disetujui Kepala Departemen. Diteruskan ke Admin GA.',
            ]);

            DB::commit();

            $this->sendNotification(
                $loanRequest->requester,
                'Pengajuan Disetujui Kepala',
                'Pengajuan #' . $loanRequest->id . ' (' . $loanRequest->purpose . ') disetujui oleh ' . $user->full_name . '. Menunggu verifikasi GA.',
                route('loan-requests.show', $loanRequest->id),
                'success'
            );

            $adminGAList = User::where('role', 'admin_ga')->get();
            foreach ($adminGAList as $adminGA) {
                $this->sendNotification(
                    $adminGA,
                    'Pengajuan Baru Menunggu Assignment',
                    'Pengajuan #' . $loanRequest->id . ' dari ' . $loanRequest->requester->full_name . ' telah disetujui Kepala dan menunggu penugasan kendaraan.',
                    route('loan-requests.show', $loanRequest->id),
                    'info'
                );
            }

            return redirect()->route('approvals.kepala.index')
                ->with('success', 'Pengajuan #' . $loanRequest->id . ' disetujui! Diteruskan ke Admin GA.');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($signaturePath) Storage::disk('public')->delete($signaturePath);
            Log::error('Error approveKepala', ['error' => $e->getMessage(), 'loan_request_id' => $loanRequest->id]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // =========================================================
    // 👔 KEPALA DEPARTEMEN — REJECT
    // =========================================================
    public function rejectKepala(Request $request, LoanRequest $loanRequest)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->isKepalaDepartemen()) {
            abort(403, 'Akses ditolak.');
        }

        if ($loanRequest->unit_id !== $user->unit_id) {
            abort(403, 'Anda tidak bisa reject request dari unit lain.');
        }

        if ($loanRequest->status !== 'submitted') {
            return back()->with('error', 'Pengajuan tidak bisa ditolak. Status: ' . $loanRequest->status);
        }

        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            LoanApproval::create([
                'loan_request_id'    => $loanRequest->id,
                'approver_id'        => $user->id,
                'approval_level'     => 'kepala',
                'decision'           => 'rejected',
                'reason'             => $request->reason,
                'approver_signature' => null,
                'decided_at'         => now(),
            ]);

            $loanRequest->update(['status' => 'rejected']);

            LoanStatusLog::create([
                'loan_request_id' => $loanRequest->id,
                'from_status'     => 'submitted',
                'to_status'       => 'rejected',
                'changed_by'      => $user->id,
                'change_note'     => 'Ditolak Kepala Departemen: ' . $request->reason,
            ]);

            DB::commit();

            $this->sendNotification(
                $loanRequest->requester,
                'Pengajuan Ditolak Kepala',
                'Pengajuan #' . $loanRequest->id . ' (' . $loanRequest->purpose . ') ditolak oleh ' . $user->full_name . '.',
                route('loan-requests.show', $loanRequest->id),
                'danger',
                $request->reason
            );

            return redirect()->route('approvals.kepala.index')
                ->with('success', 'Pengajuan #' . $loanRequest->id . ' berhasil ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // =========================================================
    // 🧑‍💼 ADMIN HR — INDEX
    // =========================================================
    public function indexHR()                                          // ✅ rename dari indexAkuntansi
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->isAdminHR()) {                                     // ✅ isAdminHR
            abort(403, 'Akses ditolak.');
        }

        $pendingRequests = LoanRequest::with([
            'requester',
            'requester.unit',
            'vehicle',
            'approvals.approver',
        ])
            ->where('unit_id', $user->unit_id)
            ->where('status', 'submitted')
            ->latest()
            ->get();

        return view('approvals.hr.index', compact('pendingRequests')); // ✅ view hr
    }

    // =========================================================
    // 🧑‍💼 ADMIN HR — FORM APPROVE
    // =========================================================
    public function showApproveFormHR(LoanRequest $loanRequest)        // ✅ rename
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->isAdminHR()) {                                     // ✅ isAdminHR
            abort(403, 'Akses ditolak.');
        }

        if ($loanRequest->unit_id !== $user->unit_id) {
            abort(403, 'Anda tidak bisa memproses request dari unit lain.');
        }

        if ($loanRequest->status !== 'submitted') {
            return redirect()->route('approvals.hr.index')             // ✅ route hr
                ->with('error', 'Pengajuan tidak bisa diproses. Status: ' . $loanRequest->status);
        }

        $loanRequest->load([
            'requester',
            'requester.unit',
            'vehicle',
            'attachments',
            'approvals.approver',
        ]);

        return view('approvals.hr.approve', compact('loanRequest'));    // ✅ view hr
    }

    // =========================================================
    // 🧑‍💼 ADMIN HR — APPROVE
    // =========================================================
    public function approveHR(Request $request, LoanRequest $loanRequest) // ✅ rename
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->isAdminHR()) {                                     // ✅ isAdminHR
            abort(403, 'Akses ditolak.');
        }

        if ($loanRequest->unit_id !== $user->unit_id) {
            abort(403, 'Anda tidak bisa memproses request dari unit lain.');
        }

        if ($loanRequest->status !== 'submitted') {
            return back()->with('error', 'Pengajuan tidak bisa diproses. Status: ' . $loanRequest->status);
        }

        $request->validate([
            'signature' => 'required|string',
        ]);

        $signaturePath = null;
        try {
            DB::beginTransaction();

            if ($request->filled('signature') && str_starts_with($request->signature, 'data:image')) {
                $signaturePath = $this->saveSignature(
                    $request->signature,
                    'approver_' . $loanRequest->id . '_' . $user->id
                );
            }

            LoanApproval::create([
                'loan_request_id'    => $loanRequest->id,
                'approver_id'        => $user->id,
                'approval_level'     => 'kepala',                      // ✅ level 1, sama seperti kepala departemen
                'decision'           => 'approved',
                'reason'             => null,
                'approver_signature' => $signaturePath,
                'decided_at'         => now(),
            ]);

            // ✅ FIX BUG: status ke approved_kepala (bukan approved_ga langsung)
            $loanRequest->forceFill([
                'status'           => 'approved_kepala',
                'kepala_signature' => $signaturePath,
            ])->save();

            LoanStatusLog::create([
                'loan_request_id' => $loanRequest->id,
                'from_status'     => 'submitted',
                'to_status'       => 'approved_kepala',                // ✅ fix
                'changed_by'      => $user->id,
                'change_note'     => 'Disetujui Admin HR: ' . $user->full_name . '. Diteruskan ke Admin GA.',
            ]);

            DB::commit();

            $this->sendNotification(
                $loanRequest->requester,
                'Pengajuan Disetujui Admin HR',
                'Pengajuan #' . $loanRequest->id . ' (' . $loanRequest->purpose . ') disetujui oleh ' . $user->full_name . '. Menunggu penugasan kendaraan.',
                route('loan-requests.show', $loanRequest->id),
                'success'
            );

            $adminGAList = User::where('role', 'admin_ga')->get();
            foreach ($adminGAList as $adminGA) {
                $this->sendNotification(
                    $adminGA,
                    'Pengajuan Baru Menunggu Assignment',
                    'Pengajuan #' . $loanRequest->id . ' dari ' . $loanRequest->requester->full_name . ' telah disetujui Admin HR dan menunggu penugasan kendaraan.',
                    route('admin.loan-requests.index'),
                    'info'
                );
            }

            return redirect()->route('approvals.hr.index')             // ✅ route hr
                ->with('success', 'Pengajuan #' . $loanRequest->id . ' disetujui! Diteruskan ke Admin GA.');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($signaturePath) Storage::disk('public')->delete($signaturePath);
            Log::error('Error approveHR', ['error' => $e->getMessage(), 'loan_request_id' => $loanRequest->id]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // =========================================================
    // 🧑‍💼 ADMIN HR — REJECT
    // =========================================================
    public function rejectHR(Request $request, LoanRequest $loanRequest) // ✅ rename
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->isAdminHR()) {                                     // ✅ isAdminHR
            abort(403, 'Akses ditolak.');
        }

        if ($loanRequest->unit_id !== $user->unit_id) {
            abort(403, 'Anda tidak bisa memproses request dari unit lain.');
        }

        if ($loanRequest->status !== 'submitted') {
            return back()->with('error', 'Pengajuan tidak bisa ditolak. Status: ' . $loanRequest->status);
        }

        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            LoanApproval::create([
                'loan_request_id'    => $loanRequest->id,
                'approver_id'        => $user->id,
                'approval_level'     => 'kepala',
                'decision'           => 'rejected',
                'reason'             => $request->reason,
                'approver_signature' => null,
                'decided_at'         => now(),
            ]);

            $loanRequest->update(['status' => 'rejected']);

            LoanStatusLog::create([
                'loan_request_id' => $loanRequest->id,
                'from_status'     => 'submitted',
                'to_status'       => 'rejected',
                'changed_by'      => $user->id,
                'change_note'     => 'Ditolak Admin HR: ' . $request->reason,
            ]);

            DB::commit();

            $this->sendNotification(
                $loanRequest->requester,
                'Pengajuan Ditolak Admin HR',
                'Pengajuan #' . $loanRequest->id . ' (' . $loanRequest->purpose . ') ditolak oleh ' . $user->full_name . '.',
                route('loan-requests.show', $loanRequest->id),
                'danger',
                $request->reason
            );

            return redirect()->route('approvals.hr.index')             // ✅ route hr
                ->with('success', 'Pengajuan #' . $loanRequest->id . ' berhasil ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}