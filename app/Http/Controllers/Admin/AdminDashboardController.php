<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanRequest;
use App\Models\LoanApproval;
use App\Models\ReturnExpense;
use App\Models\Unit;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleReturn;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        return match(true) {
            $user->isAdminGA()          => $this->adminGa(),
            $user->isAdminAkuntansi()   => $this->adminAkuntansi(),
            $user->isKepalaDepartemen() => $this->kepalaDepartemen(),
            $user->isRegularUser()      => redirect()->route('dashboard'),
            default                     => abort(403, 'Role tidak dikenali.'),
        };
    }

    // =========================================================
    // 👑 ADMIN GA
    // =========================================================
    private function adminGa()
    {
        // ✅ loan_requests.status ENUM:
        // submitted|approved_kepala|approved_ga|assigned|in_use|returned|rejected
        $stats = [
            'pending_kepala'  => LoanRequest::where('status', 'submitted')->count(),
            'need_ga_approve' => LoanRequest::where('status', 'approved_kepala')->count(),
            'need_assignment' => LoanRequest::where('status', 'approved_ga')->count(),
            'in_use'          => LoanRequest::where('status', 'in_use')->count(),
        ];

        // ✅ vehicles.status ENUM: available|in_use|maintenance|retired
        $vehicleStats = [
            'available'   => Vehicle::where('status', 'available')->count(),
            'in_use'      => Vehicle::where('status', 'in_use')->count(),
            'maintenance' => Vehicle::where('status', 'maintenance')->count(),
            'retired'     => Vehicle::where('status', 'retired')->count(),
        ];

        // Pengajuan masuk — status: submitted
        $pendingRequests = LoanRequest::where('status', 'submitted')
            ->with(['requester', 'requester.unit', 'vehicle'])
            ->latest()
            ->take(8)
            ->get();

        // Sudah disetujui kepala, perlu approve GA
        $needGaApproval = LoanRequest::where('status', 'approved_kepala')
            ->with(['requester', 'requester.unit', 'vehicle'])
            ->latest()
            ->take(8)
            ->get();

        // Sudah disetujui GA, perlu assign kendaraan
        $needAssignment = LoanRequest::where('status', 'approved_ga')
            ->with(['requester', 'requester.unit', 'vehicle'])
            ->latest()
            ->take(8)
            ->get();

        // Sedang aktif dipinjam
        $activeLoans = LoanRequest::where('status', 'in_use')
            ->with(['requester', 'requester.unit', 'assignment.assignedVehicle'])
            ->latest()
            ->take(8)
            ->get();

        // Kendaraan tersedia untuk ditugaskan
        $availableVehicles = Vehicle::where('status', 'available')->get();

        // ✅ View: resources/views/admin/dashboard/admin-ga.blade.php
        return view('admin.dashboard.admin-ga', compact(
            'stats',
            'vehicleStats',
            'pendingRequests',
            'needGaApproval',
            'needAssignment',
            'activeLoans',
            'availableVehicles'
        ));
    }

    // =========================================================
    // 💼 ADMIN AKUNTANSI
    // =========================================================
    private function adminAkuntansi()
    {
        $now = now();

        // ✅ return_expenses.amount (decimal 14,2)
        // ✅ return_expenses.type ENUM: fuel|toll|parking|repair|other
        $stats = [
            'total_expense_month' => ReturnExpense::whereMonth('created_at', $now->month)
                                        ->whereYear('created_at', $now->year)
                                        ->sum('amount'),
            'returns_month'       => VehicleReturn::whereMonth('created_at', $now->month)
                                        ->whereYear('created_at', $now->year)
                                        ->count(),
            'pending_return'      => LoanRequest::where('status', 'in_use')->count(),
            'completed_return'    => LoanRequest::where('status', 'returned')->count(),
        ];

        // Breakdown per jenis pengeluaran bulan ini
        $expenseByType = ReturnExpense::selectRaw('type, SUM(amount) as total, COUNT(*) as jumlah')
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->groupBy('type')
            ->get()
            ->keyBy('type');

        // Riwayat pengembalian terbaru
        // ✅ returns.returned_at, returns.odometer_km_end, returns.return_note
        $recentReturns = VehicleReturn::with([
                'loanRequest.requester',
                'loanRequest.unit',
                'loanRequest.assignment.assignedVehicle',
                'expenses',
            ])
            ->latest('returned_at')
            ->take(10)
            ->get();

        // Pengeluaran terbesar bulan ini
        $topExpenses = ReturnExpense::with([
                'vehicleReturn.loanRequest.requester',
                'vehicleReturn.loanRequest.unit',
            ])
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->orderByDesc('amount')
            ->take(10)
            ->get();

        // Peminjaman aktif (belum kembali)
        $activeLoans = LoanRequest::where('status', 'in_use')
            ->with(['requester', 'requester.unit', 'assignment.assignedVehicle'])
            ->latest()
            ->take(8)
            ->get();

        // ✅ View: resources/views/admin/dashboard/admin-akuntansi.blade.php
        return view('admin.dashboard.admin-akuntansi', compact(
            'stats',
            'expenseByType',
            'recentReturns',
            'topExpenses',
            'activeLoans'
        ));
    }

    // =========================================================
    // 👔 KEPALA DEPARTEMEN
    // =========================================================
    private function kepalaDepartemen()
    {
        /** @var User $user */
        $user   = Auth::user();
        $unitId = $user->unit_id;

        // ✅ loan_approvals.approval_level ENUM: kepala|ga
        // ✅ loan_approvals.decision ENUM: approved|rejected
        $stats = [
            'need_my_approval' => LoanRequest::where('unit_id', $unitId)
                                    ->where('status', 'submitted')
                                    ->count(),
            'approved_by_me'   => LoanApproval::where('approver_id', $user->id)
                                    ->where('approval_level', 'kepala')
                                    ->where('decision', 'approved')
                                    ->count(),
            'rejected_by_me'   => LoanApproval::where('approver_id', $user->id)
                                    ->where('approval_level', 'kepala')
                                    ->where('decision', 'rejected')
                                    ->count(),
            'in_use_unit'      => LoanRequest::where('unit_id', $unitId)
                                    ->where('status', 'in_use')
                                    ->count(),
        ];

        // Pengajuan unit yang menunggu persetujuan kepala
        // ✅ TIDAK ada relationship 'vehicle' — pakai 'vehicle'
        $pendingApproval = LoanRequest::where('unit_id', $unitId)
            ->where('status', 'submitted')
            ->with(['requester', 'vehicle'])
            ->latest()
            ->get();

        // Riwayat semua request unit ini
        $unitRequests = LoanRequest::where('unit_id', $unitId)
            ->with([
                'requester',
                'vehicle',
                'approvals',
                'assignment.assignedVehicle',
            ])
            ->latest()
            ->take(10)
            ->get();

        // ✅ units.kepala_departemen_id FK → users.id
        $unit = Unit::with(['kepalaDepartemen'])->find($unitId);

        // ✅ View: resources/views/admin/dashboard/kepala-departemen.blade.php
        return view('admin.dashboard.kepala-departemen', compact(
            'stats',
            'pendingApproval',
            'unitRequests',
            'unit'
        ));
    }
}
