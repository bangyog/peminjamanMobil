<?php

namespace App\Http\Controllers;

use App\Models\LoanRequest;
use App\Models\LoanApproval;
use App\Models\Vehicle;
use App\Models\Unit;
use App\Models\ReturnExpense;
use App\Models\VehicleReturn;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // ✅ Pakai role langsung — sesuai DB ENUM:
        // admin_ga | admin_akuntansi | kepala_departemen | user
        return match ($user->role) {
            'admin_ga'          => $this->adminGa(),
            'admin_akuntansi'   => $this->adminAkuntansi(),
            'kepala_departemen' => $this->kepalaDepartemen(),
            default             => $this->userDashboard($user),
        };
    }

    // =========================================================
    // 👑 ADMIN GA
    // =========================================================
    private function adminGa()
    {
        // ✅ Status ENUM DB: submitted|approved_kepala|approved_ga|assigned|in_use|returned|rejected
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

        // ✅ resources/views/admin/dashboard/admin-ga.blade.php
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
        /** @var \App\Models\User $user */
        $user   = Auth::user();
        $unitId = $user->unit_id;

        $stats = [
            'need_my_approval' => LoanRequest::where('unit_id', $unitId)
                                    ->where('status', 'submitted')
                                    ->count(),
            'approved_by_me'   => 0, // akuntansi tidak punya approval_level
            'rejected_by_me'   => 0, // akuntansi tidak punya approval_level
            'in_use_unit'      => LoanRequest::where('unit_id', $unitId)
                                    ->where('status', 'in_use')
                                    ->count(),
        ];

        $pendingApproval = LoanRequest::where('unit_id', $unitId)
            ->where('status', 'submitted')
            ->with(['requester', 'vehicle'])
            ->latest()->get();

        $unitRequests = LoanRequest::where('unit_id', $unitId)
            ->with(['requester', 'vehicle', 'approvals', 'assignment.assignedVehicle'])
            ->latest()->take(10)->get();

        // ✅ units.kepala_departemen_id FK → users.id
        $unit = Unit::with(['kepalaDepartemen'])->find($unitId);

        // ✅ resources/views/admin/dashboard/admin-akuntansi.blade.php
        return view('admin.dashboard.admin-akuntansi', compact(
            'stats',
            'pendingApproval',
            'unitRequests',
            'unit'
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
                ->whereIn('status', ['pending_kepala', 'pending_akuntansi', 'pending_ga'])
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
