<?php

namespace App\Http\Controllers;

use App\Models\LoanApproval;
use App\Models\LoanRequest;
use App\Models\LoanStatusLog;
use App\Models\User;
use App\Notifications\LoanNotification; // ✅ Tambahan
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
                'approval_level'     => 'kepala', // ✅ benar
                'decision'           => 'approved',
                'reason'             => null,
                'approver_signature' => $signaturePath,
                'decided_at'         => now(),
            ]);

            // ✅ Status ke approved_kepala (bukan approved_ga)
            $loanRequest->forceFill([
                'status'           => 'approved_kepala',
                'kepala_signature' => $signaturePath,
            ])->save();

            LoanStatusLog::create([
                'loan_request_id' => $loanRequest->id,
                'from_status'     => 'submitted',
                'to_status'       => 'approved_kepala', // ✅ fix
                'changed_by'      => $user->id,
                'change_note'     => 'Disetujui Kepala Departemen. Diteruskan ke Admin GA.',
            ]);

            DB::commit();

            // ✅ Notif requester
            $this->sendNotification(
                $loanRequest->requester,
                'Pengajuan Disetujui Kepala',
                'Pengajuan #' . $loanRequest->id . ' (' . $loanRequest->purpose . ') disetujui oleh ' . $user->full_name . '. Menunggu verifikasi GA.',
                route('loan-requests.show', $loanRequest->id),
                'success'
            );

            // ✅ Notif langsung ke Admin GA (bukan Akuntansi)
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

            // ✅ Notif requester
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
    // 💼 ADMIN AKUNTANSI — INDEX
    // =========================================================
    public function indexAkuntansi()
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->isAdminAkuntansi()) {
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

        return view('approvals.akuntansi.index', compact('pendingRequests'));
    }

    // =========================================================
    // 💼 ADMIN AKUNTANSI — FORM APPROVE
    // =========================================================
    public function showApproveFormAkuntansi(LoanRequest $loanRequest)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->isAdminAkuntansi()) {
            abort(403, 'Akses ditolak.');
        }

        if ($loanRequest->unit_id !== $user->unit_id) {
            abort(403, 'Anda tidak bisa memproses request dari unit lain.');
        }

        if ($loanRequest->status !== 'submitted') {
            return redirect()->route('approvals.akuntansi.index')
                ->with('error', 'Pengajuan tidak bisa diproses. Status: ' . $loanRequest->status);
        }

        $loanRequest->load([
            'requester',
            'requester.unit',
            'vehicle',
            'attachments',
            'approvals.approver',
        ]);

        return view('approvals.akuntansi.approve', compact('loanRequest'));
    }

    // =========================================================
    // 💼 ADMIN AKUNTANSI — APPROVE
    // =========================================================
    public function approveAkuntansi(Request $request, LoanRequest $loanRequest)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->isAdminAkuntansi()) {
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

        try {
            DB::beginTransaction();

            $signaturePath = null;
            if ($request->filled('signature') && str_starts_with($request->signature, 'data:image')) {
                $signaturePath = $this->saveSignature(
                    $request->signature,
                    'approver_' . $loanRequest->id . '_' . $user->id
                );
            }

            LoanApproval::create([
                'loan_request_id'    => $loanRequest->id,
                'approver_id'        => $user->id,
                'approval_level'     => 'kepala', // ✅ Fix dari 'kepala'
                'decision'           => 'approved',
                'reason'             => null,
                'approver_signature' => $signaturePath,
                'decided_at'         => now(),
            ]);

            $loanRequest->update([
                'status'             => 'approved_ga',
                'approver_signature' => $signaturePath,
            ]);

            LoanStatusLog::create([
                'loan_request_id' => $loanRequest->id,
                'from_status'     => 'submitted',
                'to_status'       => 'approved_ga',
                'changed_by'      => $user->id,
                'change_note'     => 'Diverifikasi Admin Akuntansi: ' . $user->full_name . '. Diteruskan ke Admin GA.',
            ]);

            DB::commit();

            Log::info('Loan approved by Admin Akuntansi', [
                'loan_request_id' => $loanRequest->id,
                'approver_id'     => $user->id,
            ]);

            // ✅ Notif requester
            $this->sendNotification(
                $loanRequest->requester,
                'Pengajuan Disetujui Admin Akuntansi',
                'Pengajuan #' . $loanRequest->id . ' (' . $loanRequest->purpose . ') diverifikasi oleh ' . $user->full_name . '. Menunggu penugasan kendaraan.',
                route('loan-requests.show', $loanRequest->id),
                'success'
            );

            // ✅ Notif semua Admin GA
            $adminGAList = User::where('role', 'admin_ga')->get();
            foreach ($adminGAList as $adminGA) {
                $this->sendNotification(
                    $adminGA,
                    'Pengajuan Baru Menunggu Assignment',
                    'Pengajuan #' . $loanRequest->id . ' dari ' . $loanRequest->requester->full_name . ' telah diverifikasi akuntansi dan menunggu penugasan kendaraan.',
                    route('approvals.ga.index'),
                    'info'
                );
            }

            return redirect()->route('approvals.akuntansi.index')
                ->with('success', 'Pengajuan #' . $loanRequest->id . ' diverifikasi & diteruskan ke Admin GA!');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($signaturePath) Storage::disk('public')->delete($signaturePath);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // =========================================================
    // 💼 ADMIN AKUNTANSI — REJECT
    // =========================================================
    public function rejectAkuntansi(Request $request, LoanRequest $loanRequest)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->isAdminAkuntansi()) {
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
                'approval_level'     => 'kepala', // ✅ Fix dari 'kepala'
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
                'change_note'     => 'Ditolak Admin Akuntansi: ' . $request->reason,
            ]);

            DB::commit();

            // ✅ Notif requester
            $this->sendNotification(
                $loanRequest->requester,
                'Pengajuan Ditolak Admin Akuntansi',
                'Pengajuan #' . $loanRequest->id . ' (' . $loanRequest->purpose . ') ditolak oleh ' . $user->full_name . '.',
                route('loan-requests.show', $loanRequest->id),
                'danger',
                $request->reason
            );

            return redirect()->route('approvals.akuntansi.index')
                ->with('success', 'Pengajuan #' . $loanRequest->id . ' berhasil ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
