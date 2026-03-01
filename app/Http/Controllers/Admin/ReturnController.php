<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanRequest;
use App\Models\VehicleReturn;
use App\Models\ReturnExpense;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Notifications\LoanNotification;

class ReturnController extends Controller
{
    private function getAuthUser(): User
    {
        /** @var User $user */
        return Auth::user();
    }

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
    // INDEX — List semua return (Admin GA only)
    // ============================================================
    public function index(Request $request)
    {
        $user = $this->getAuthUser();

        if (!$user->isAdminGA()) {
            abort(403, 'Akses ditolak.');
        }

        $query = VehicleReturn::with([
            'loanRequest.requester',
            'loanRequest.unit',
            'loanRequest.assignment.assignedVehicle',
            'receivedBy',
            'expenses',
        ]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('loanRequest', function ($q) use ($search) {
                $q->whereHas('requester', function ($q2) use ($search) {
                    $q2->where('full_name', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'unprocessed') {
                $query->whereNull('received_by');
            } elseif ($request->status === 'processed') {
                $query->whereNotNull('received_by');
            }
        }

        if ($request->filled('date_from')) {
            $query->whereDate('returned_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('returned_at', '<=', $request->date_to);
        }

        $returns = $query->latest('returned_at')->paginate(15)->withQueryString();

        $stats = [
            'total_returns' => VehicleReturn::count(),
            'today'         => VehicleReturn::whereDate('returned_at', today())->count(),
            'this_month'    => VehicleReturn::whereMonth('returned_at', now()->month)
                ->whereYear('returned_at', now()->year)->count(),
            'unprocessed'   => VehicleReturn::whereNull('received_by')->count(),
        ];

        return view('admin.returns.index', compact('returns', 'stats'));
    }

    // ============================================================
    // SHOW — Detail return (Admin GA only)
    // ============================================================
    public function show(VehicleReturn $return)
    {
        $user = $this->getAuthUser();

        if (!$user->isAdminGA()) {
            abort(403, 'Akses ditolak.');
        }

        $return->load([
            'loanRequest.requester',
            'loanRequest.unit',
            'loanRequest.assignment.assignedVehicle',
            'loanRequest.approvals.approver',
            'receivedBy',
            'expenses',
            'attachments',
        ]);

        return view('admin.returns.show', compact('return'));
    }

    // ============================================================
    // PROCESS — Admin GA konfirmasi pengembalian dari user
    // ============================================================
    public function process(VehicleReturn $return)
    {
        $user = $this->getAuthUser();

        if (!$user->isAdminGA()) {
            abort(403, 'Hanya Admin GA yang dapat memproses pengembalian.');
        }

        if (!is_null($return->received_by)) {
            return back()->with('error', 'Pengembalian ini sudah pernah diproses.');
        }

        $return->load('loanRequest.requester', 'loanRequest.assignment.assignedVehicle', 'loanRequest.statusLogs');
        $loanRequest = $return->loanRequest;

        DB::beginTransaction();
        try {
            $return->update(['received_by' => $user->id]);

            if ($loanRequest?->assignment?->assignedVehicle) {
                $loanRequest->assignment->assignedVehicle
                    ->update(['status' => 'available']);
            }

            if ($loanRequest) {
                $loanRequest->statusLogs()->create([
                    'from_status' => 'returned',
                    'to_status'   => 'returned',
                    'changed_by'  => $user->id,
                    'change_note' => 'Pengembalian dikonfirmasi oleh Admin GA. Kendaraan kini tersedia.',
                    'changed_at'  => now(),
                ]);
            }

            DB::commit();

            // ✅ Notif requester — sudah di tempat yang benar (setelah commit)
            if ($loanRequest?->requester) {
                $this->sendNotification(
                    $loanRequest->requester,
                    'Pengembalian Dikonfirmasi',
                    'Pengembalian kendaraan untuk peminjaman #' . $return->loan_request_id . ' telah dikonfirmasi oleh Admin GA.',
                    route('loan-requests.show', $loanRequest),
                    'success'
                );
            }

            return redirect()
                ->route('admin.returns.show', $return)
                ->with('success', 'Pengembalian berhasil dikonfirmasi. Kendaraan kini tersedia.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Admin\ReturnController@process error', [
                'message'   => $e->getMessage(),
                'return_id' => $return->id,
                'user_id'   => $user->id,
            ]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
