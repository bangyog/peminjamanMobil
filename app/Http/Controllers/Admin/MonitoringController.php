<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanRequest;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class MonitoringController extends Controller
{
    private function authorizeAdminHR(): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->isAdminHR()) {
            abort(403, 'Akses ditolak. Hanya Admin HR yang dapat mengakses halaman ini.');
        }
    }

    public function index(Request $request)
    {
        $this->authorizeAdminHR();

        $query = LoanRequest::with([
            'requester',
            'requester.unit',
            'vehicle',
            'approvals',
            'assignment.assignedVehicle',
        ]);

        // ✅ Default hanya tampilkan 'returned' — kecuali user pilih status lain
        $status = $request->filled('status') ? $request->status : 'returned';
        $query->where('status', $status);

        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }
        if ($request->filled('start_date')) {
            $query->whereDate('depart_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('depart_at', '<=', $request->end_date);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('requester', function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%");
            });
        }

        $histories = $query->latest()->paginate(20)->withQueryString();

        // ✅ Stats selalu global (tidak ikut filter)
        $stats = [
            'total_all'    => LoanRequest::where('status', 'returned')->count(),
            'in_use_all'   => LoanRequest::where('status', 'in_use')->count(),
            'returned_all' => LoanRequest::where('status', 'returned')->count(),
            'rejected_all' => LoanRequest::where('status', 'rejected')->count(),
        ];

        $units = Unit::orderBy('name')->get();

        // ✅ Hanya status yang relevan untuk history selesai
        $statuses = [
            'returned' => '✔️ Sudah Dikembalikan',
            'rejected' => '❌ Ditolak',
        ];

        return view('admin.monitoring.history', compact(
            'histories', 'stats', 'units', 'statuses'
        ));
    }

    public function exportPdf(Request $request)
    {
        $this->authorizeAdminHR();

        $query = LoanRequest::with([
            'requester',
            'requester.unit',
            'vehicle',
            'assignment.assignedVehicle',
        ]);

        // ✅ Default export juga hanya returned
        $status = $request->filled('status') ? $request->status : 'returned';
        $query->where('status', $status);

        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }
        if ($request->filled('start_date')) {
            $query->whereDate('depart_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('depart_at', '<=', $request->end_date);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('requester', function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%");
            });
        }

        $histories = $query->latest()->get();

        $statuses = [
            'returned' => 'Sudah Dikembalikan',
            'rejected' => 'Ditolak',
        ];

        $filterInfo = [
            'unit'         => $request->filled('unit_id')
                                ? Unit::find($request->unit_id)?->name ?? 'Semua Unit'
                                : 'Semua Unit',
            'status'       => $statuses[$status] ?? 'Semua Status',
            'start_date'   => $request->start_date ?? '-',
            'end_date'     => $request->end_date ?? '-',
            'generated_at' => now()->format('d/m/Y H:i'),
            'generated_by' => Auth::user()->full_name,
        ];

        $pdf = Pdf::loadView('admin.monitoring.history-pdf', compact('histories', 'filterInfo', 'statuses'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('history-peminjaman-' . now()->format('Y-m-d') . '.pdf');
    }
}