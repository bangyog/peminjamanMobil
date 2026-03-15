<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanRequest;
use App\Models\LoanApproval;
use App\Models\Unit;
use App\Models\User;
use App\Models\Vehicle;
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
            $user->isAdminHR()          => $this->adminHr(),           // ✅ ganti isAdminAkuntansi → isAdminHR
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
    // 🧑‍💼 ADMIN HR
    // Sama seperti Kepala Departemen (kelola unit sendiri)
    // + tambahan: ringkasan history seluruh unit
    // =========================================================
    private function adminHr()                                         // ✅ rename dari adminAkuntansi
    {
        /** @var User $user */
        $user   = Auth::user();
        $unitId = $user->unit_id;

        // Stats unit sendiri (approval activity)
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

        // Pengajuan di unit sendiri yang menunggu persetujuan
        $pendingApproval = LoanRequest::where('unit_id', $unitId)
            ->where('status', 'submitted')
            ->with(['requester', 'vehicle'])
            ->latest()->get();

        // Riwayat request unit sendiri
        $unitRequests = LoanRequest::where('unit_id', $unitId)
            ->with(['requester', 'vehicle', 'approvals', 'assignment.assignedVehicle'])
            ->latest()->take(10)->get();

        $unit = Unit::with(['kepalaDepartemen'])->find($unitId);

        // ✅ TAMBAHAN Admin HR: ringkasan history seluruh unit (lintas unit)
        $historyStats = [
            'total_all'    => LoanRequest::count(),
            'in_use_all'   => LoanRequest::where('status', 'in_use')->count(),
            'returned_all' => LoanRequest::where('status', 'returned')->count(),
            'rejected_all' => LoanRequest::where('status', 'rejected')->count(),
        ];

        // ✅ View: resources/views/admin/dashboard/admin-hr.blade.php
        return view('admin.dashboard.admin-hr', compact(
            'stats',
            'pendingApproval',
            'unitRequests',
            'unit',
            'historyStats'
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

        $pendingApproval = LoanRequest::where('unit_id', $unitId)
            ->where('status', 'submitted')
            ->with(['requester', 'vehicle'])
            ->latest()->get();

        $unitRequests = LoanRequest::where('unit_id', $unitId)
            ->with(['requester', 'vehicle', 'approvals', 'assignment.assignedVehicle'])
            ->latest()->take(10)->get();

        $unit = Unit::with(['kepalaDepartemen'])->find($unitId);

        return view('admin.dashboard.kepala-departemen', compact(
            'stats',
            'pendingApproval',
            'unitRequests',
            'unit'
        ));
    }
}