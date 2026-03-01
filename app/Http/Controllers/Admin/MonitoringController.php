<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReturnExpense;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonitoringController extends Controller
{
    /**
     * ✅ Role check — Admin Akuntansi & Admin GA
     */
    private function authorizeAdminGA(): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isAdminGA()) {
            abort(403, 'Akses ditolak.');
        }
    }

    /**
     * Monitoring expenses
     */
    public function expenses(Request $request)
    {
        $this->authorizeAdminGA(); 

        $query = ReturnExpense::with([
                // ✅ FIX BUG 1: requester (bukan user) — sesuai FK requester_id
                'vehicleReturn.loanRequest.requester.unit',
                // ✅ kendaraan dari assignment
                'vehicleReturn.loanRequest.assignment.assignedVehicle',
                // ✅ FIX BUG 8: createdBy (bukan recordedBy) — sesuai FK created_by
                'createdBy',
            ])
            ->orderBy('created_at', 'desc');

        // Filter tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // ✅ FIX BUG 2: param 'expense_type' — sesuai nama field di form view
        if ($request->filled('expense_type')) {
            $query->where('type', $request->expense_type);
        }

        // ✅ FIX BUG 3: filter unit_id (bukan unit_code — tidak ada di loan_requests)
        if ($request->filled('unit_id')) {
            $query->whereHas('vehicleReturn.loanRequest', function ($q) use ($request) {
                $q->where('unit_id', $request->unit_id);
            });
        }

        $expenses = $query->paginate(20)->withQueryString();

        // ✅ Stats query ikut filter aktif
        $statsQuery = ReturnExpense::query();

        if ($request->filled('start_date')) {
            $statsQuery->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $statsQuery->whereDate('created_at', '<=', $request->end_date);
        }
        // ✅ FIX BUG 9: param expense_type di stats query juga
        if ($request->filled('expense_type')) {
            $statsQuery->where('type', $request->expense_type);
        }
        if ($request->filled('unit_id')) {
            $statsQuery->whereHas('vehicleReturn.loanRequest', function ($q) use ($request) {
                $q->where('unit_id', $request->unit_id);
            });
        }

        $stats = [
            // ✅ FIX BUG 6: key 'total_expenses' — sesuai yang dipakai di view
            'total_expenses'   => ReturnExpense::sum('amount'),
            // ✅ Bulan ini — selalu global
            'total_this_month' => ReturnExpense::whereMonth('created_at', now()->month)
                                     ->whereYear('created_at', now()->year)
                                     ->sum('amount'),
            // ✅ Total record — selalu global
            'total_records'    => ReturnExpense::count(),

            // Stats ikut filter aktif (untuk info tambahan jika dibutuhkan view)
            'total_filtered'   => (clone $statsQuery)->sum('amount'),
            'count_filtered'   => (clone $statsQuery)->count(),

            // ✅ FIX BUG 7: tambah 'repair' — sesuai ENUM DB (fuel|toll|parking|repair|other)
            'by_type' => [
                'fuel'    => ReturnExpense::where('type', 'fuel')->sum('amount'),
                'toll'    => ReturnExpense::where('type', 'toll')->sum('amount'),
                'parking' => ReturnExpense::where('type', 'parking')->sum('amount'),
                'repair'  => ReturnExpense::where('type', 'repair')->sum('amount'), // ✅ ditambah
                'other'   => ReturnExpense::where('type', 'other')->sum('amount'),
            ],
        ];

        // Units untuk dropdown filter
        $units = Unit::orderBy('name')->get();

        return view('admin.monitoring.expenses', compact('expenses', 'stats', 'units'));
    }

    /**
     * Export expenses ke CSV
     */
    public function exportExpenses(Request $request)
    {
        $this->authorizeAdminGA();

        $query = ReturnExpense::with([
            // ✅ FIX BUG 1: requester (bukan user)
            'vehicleReturn.loanRequest.requester.unit',
            'vehicleReturn.loanRequest.assignment.assignedVehicle',
        ]);

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        // ✅ FIX BUG 2: expense_type
        if ($request->filled('expense_type')) {
            $query->where('type', $request->expense_type);
        }
        // ✅ FIX BUG 3: unit_id
        if ($request->filled('unit_id')) {
            $query->whereHas('vehicleReturn.loanRequest', function ($q) use ($request) {
                $q->where('unit_id', $request->unit_id);
            });
        }

        $expenses = $query->orderBy('created_at', 'desc')->get();

        // ✅ Label ENUM → Indonesia untuk CSV
        $typeLabels = [
            'fuel'    => 'BBM',
            'toll'    => 'Tol',
            'parking' => 'Parkir',
            'repair'  => 'Servis / Perbaikan',
            'other'   => 'Lainnya',
        ];

        $filename = 'pengeluaran-kendaraan-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($expenses, $typeLabels) {
            // ✅ BOM UTF-8 supaya Excel bisa baca
            echo "\xEF\xBB\xBF";
            echo "ID,Tanggal,Unit,Peminjam,Kendaraan,Tipe Pengeluaran,Jumlah (Rp),Keterangan\n";

            foreach ($expenses as $expense) {
                $loanRequest     = $expense->vehicleReturn?->loanRequest;
                // ✅ FIX BUG 1: requester (bukan user)
                $requester       = $loanRequest?->requester;
                $unit            = $requester?->unit;
                $assignedVehicle = $loanRequest?->assignment?->assignedVehicle;

                echo sprintf(
                    "%d,%s,%s,%s,%s,%s,%s,%s\n",
                    $expense->id,
                    $expense->created_at->format('d/m/Y'),
                    // ✅ FIX BUG 4: $unit->name (bukan full_name — units punya kolom 'name')
                    $unit?->name ?? '-',
                    $requester?->full_name ?? '-',
                    $assignedVehicle
                        ? ($assignedVehicle->brand . ' ' . $assignedVehicle->model
                            . ' (' . $assignedVehicle->plate_no . ')')
                        : '-',
                    // ✅ Label Indonesia dari mapping
                    $typeLabels[$expense->type] ?? ucfirst($expense->type ?? '-'),
                    number_format($expense->amount, 0, ',', '.'),
                    // ✅ FIX BUG 5: description (bukan notes) — sesuai kolom DB
                    '"' . str_replace('"', '""', $expense->description ?? '') . '"'
                );
            }
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
