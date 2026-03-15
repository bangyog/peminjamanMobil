<?php

namespace App\Http\Controllers;

use App\Models\LoanRequest;
use App\Models\LoanApproval;
use App\Models\Vehicle;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return match ($user->role) {
            'admin_ga'          => $this->adminGa(),
            'admin_hr'          => $this->adminHr(),
            'kepala_departemen' => $this->kepalaDepartemen(),
            default             => $this->userDashboard($user),
        };
    }

    // =========================================================
    // 👑 ADMIN GA
    // =========================================================
    private function adminGa()
    {
        $stats = [
            'pending_kepala'  => LoanRequest::where('status', 'submitted')->count(),
            'need_ga_approve' => LoanRequest::where('status', 'approved_kepala')->count(),
            'need_assignment' => LoanRequest::where('status', 'approved_ga')->count(),
            'in_use'          => LoanRequest::where('status', 'in_use')->count(),
        ];

        $vehicleStats = [
            'available'   => Vehicle::where('status', 'available')->count(),
            'in_use'      => Vehicle::where('status', 'in_use')->count(),
            'maintenance' => Vehicle::where('status', 'maintenance')->count(),
            'retired'     => Vehicle::where('status', 'retired')->count(),
        ];

        $pendingRequests = LoanRequest::where('status', 'submitted')
            ->with(['requester', 'requester.unit', 'vehicle'])
            ->latest()->take(8)->get();

        $needGaApproval = LoanRequest::where('status', 'approved_kepala')
            ->with(['requester', 'requester.unit', 'vehicle'])
            ->latest()->take(8)->get();

        $needAssignment = LoanRequest::where('status', 'approved_ga')
            ->with(['requester', 'requester.unit', 'vehicle'])
            ->latest()->take(8)->get();

        $activeLoans = LoanRequest::where('status', 'in_use')
            ->with(['requester', 'requester.unit', 'assignment.assignedVehicle'])
            ->latest()->take(8)->get();

        $availableVehicles = Vehicle::where('status', 'available')->get();

        return view('admin.dashboard.admin-ga', compact(
            'stats', 'vehicleStats', 'pendingRequests',
            'needGaApproval', 'needAssignment', 'activeLoans', 'availableVehicles'
        ));
    }

    // =========================================================
    // 🧑‍💼 ADMIN HR
    // Sama seperti Kepala Departemen (kelola unit sendiri)
    // + tambahan: ringkasan history seluruh unit
    // =========================================================
    private function adminHr()
    {
        /** @var \App\Models\User $user */
        $user   = Auth::user();
        $unitId = $user->unit_id;

        // Stats unit sendiri (sama seperti kepala departemen)
        $stats = [
            'need_my_approval' => LoanRequest::where('unit_id', $unitId)
                ->where('status', 'submitted')->count(),
            'approved_by_me'   => LoanApproval::where('approver_id', $user->id)
                ->where('approval_level', 'kepala')
                ->where('decision', 'approved')->count(),
            'rejected_by_me'   => LoanApproval::where('approver_id', $user->id)
                ->where('approval_level', 'kepala')
                ->where('decision', 'rejected')->count(),
            'in_use_unit'      => LoanRequest::where('unit_id', $unitId)
                ->where('status', 'in_use')->count(),
        ];

        // Peminjaman pending di unit sendiri (untuk approval)
        $pendingApproval = LoanRequest::where('unit_id', $unitId)
            ->where('status', 'submitted')
            ->with(['requester', 'vehicle'])
            ->latest()->get();

        // Riwayat unit sendiri
        $unitRequests = LoanRequest::where('unit_id', $unitId)
            ->with(['requester', 'vehicle', 'approvals', 'assignment.assignedVehicle'])
            ->latest()->take(10)->get();

        $unit = Unit::with(['kepalaDepartemen'])->find($unitId);

        // ✅ TAMBAHAN Admin HR: ringkasan history seluruh unit
        $historyStats = [
            'total_all'    => LoanRequest::count(),
            'in_use_all'   => LoanRequest::where('status', 'in_use')->count(),
            'returned_all' => LoanRequest::where('status', 'returned')->count(),
            'rejected_all' => LoanRequest::where('status', 'rejected')->count(),
        ];

        return view('admin.dashboard.admin-hr', compact(
            'stats',
            'pendingApproval',
            'unitRequests',
            'unit',
            'historyStats'  // ✅ extra untuk section history di blade
        ));
    }

   // =========================================================
    // 👔 KEPALA DEPARTEMEN
    // =========================================================
    private function kepalaDepartemen()
    {
        /** @var \App\Models\User $user */
        $user   = Auth::user();
        $unitId = $user->unit_id;

        // ✅ loan_approvals.approval_level ENUM: kepala|ga
        // ✅ loan_approvals.decision ENUM: approved|rejected
        $stats = [
            'need_my_approval' => LoanRequest::where('unit_id', $unitId)
                ->where('status', 'submitted')->count(),
            'approved_by_me'   => LoanApproval::where('approver_id', $user->id)
                ->where('approval_level', 'kepala')
                ->where('decision', 'approved')->count(),
            'rejected_by_me'   => LoanApproval::where('approver_id', $user->id)
                ->where('approval_level', 'kepala')
                ->where('decision', 'rejected')->count(),
            'in_use_unit'      => LoanRequest::where('unit_id', $unitId)
                ->where('status', 'in_use')->count(),
        ];

        // ✅ TIDAK ada relationship 'vehicle' — pakai 'vehicle'
        $pendingApproval = LoanRequest::where('unit_id', $unitId)
            ->where('status', 'submitted')
            ->with(['requester', 'vehicle'])
            ->latest()->get();

        $unitRequests = LoanRequest::where('unit_id', $unitId)
            ->with(['requester', 'vehicle', 'approvals', 'assignment.assignedVehicle'])
            ->latest()->take(10)->get();

        // ✅ units.kepala_departemen_id FK → users.id
        $unit = Unit::with(['kepalaDepartemen'])->find($unitId);

        // ✅ resources/views/admin/dashboard/kepala-departemen.blade.php
        return view('admin.dashboard.kepala-departemen', compact(
            'stats',
            'pendingApproval',
            'unitRequests',
            'unit'
        ));
    }


    // =========================================================
    // 👤 USER BIASA
    // =========================================================
    private function userDashboard($user)
    {
        // Get user's loan requests statistics
        $stats = [
            'total' => LoanRequest::where('requester_id', $user->id)->count(),
            'pending' => LoanRequest::where('requester_id', $user->id)
                ->whereIn('status', ['pending_kepala', 'pending_HR', 'pending_ga'])
                ->count(),
            'approved' => LoanRequest::where('requester_id', $user->id)
                ->where('status', 'approved')
                ->count(),
            'in_use' => LoanRequest::where('requester_id', $user->id)
                ->where('status', 'in_use')
                ->count(),
            'completed' => LoanRequest::where('requester_id', $user->id)
                ->where('status', 'completed')
                ->count(),
            'rejected' => LoanRequest::where('requester_id', $user->id)
                ->whereIn('status', ['rejected', 'cancelled'])
                ->count(),
        ];

        // Get recent loan requests
        $recentLoans = LoanRequest::with(['vehicle', 'assignment.assignedVehicle'])
            ->where('requester_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // Get active loan (in use)
        $activeLoan = LoanRequest::with(['assignment.assignedVehicle'])
            ->where('requester_id', $user->id)
            ->where('status', 'in_use')
            ->first();

        return view('dashboard', compact('stats', 'recentLoans', 'activeLoan'));
    }
}