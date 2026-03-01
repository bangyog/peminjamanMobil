<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VehicleController extends Controller
{
    private function getAuthUser(): User
    {
        /** @var User $user */
        $user = Auth::user();
        return $user;
    }

    public function index(Request $request)
    {
        if (!$this->getAuthUser()->isAdminGA()) {
            abort(403, 'Akses ditolak. Hanya Admin GA.');
        }

        $query = Vehicle::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('plate_no', 'like', "%{$search}%")
                  ->orWhere('unit_code', 'like', "%{$search}%"); // ✅ unit_code ADA di DB
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $vehicles = $query->orderBy('brand')
            ->orderBy('model')
            ->paginate(10)
            ->withQueryString();

        // ✅ FIX: 'retired' sesuai ENUM DB
        $stats = [
            'total'       => Vehicle::count(),
            'available'   => Vehicle::where('status', 'available')->count(),
            'in_use'      => Vehicle::where('status', 'in_use')->count(),
            'maintenance' => Vehicle::where('status', 'maintenance')->count(),
            'retired'     => Vehicle::where('status', 'retired')->count(),
        ];

        return view('admin.vehicles.index', compact('vehicles', 'stats'));
    }

    public function create()
    {
        if (!$this->getAuthUser()->isAdminGA()) {
            abort(403, 'Akses ditolak.');
        }

        return view('admin.vehicles.create');
    }

    public function store(Request $request)
    {
        if (!$this->getAuthUser()->isAdminGA()) {
            abort(403, 'Akses ditolak.');
        }

        // ✅ Kolom sesuai DB: unit_code(ada), brand, model, plate_no,
        //    seat_capacity, status(retired), odometer_km, notes
        //    HAPUS: color, year, fuel_level — tidak ada di DB
        $validated = $request->validate([
            'unit_code'     => 'nullable|string|max:50|unique:vehicles,unit_code',
            'brand'         => 'required|string|max:50',
            'model'         => 'required|string|max:100',
            'plate_no'      => 'required|string|max:20|unique:vehicles,plate_no',
            'seat_capacity' => 'required|integer|min:1|max:50',
            'status'        => 'required|in:available,in_use,maintenance,retired', // ✅ retired
            'odometer_km'   => 'required|integer|min:0',
            'notes'         => 'nullable|string|max:1000',
        ], [
            'unit_code.unique'       => 'Kode unit sudah digunakan',
            'brand.required'         => 'Merek wajib diisi',
            'model.required'         => 'Model wajib diisi',
            'plate_no.required'      => 'Nomor polisi wajib diisi',
            'plate_no.unique'        => 'Nomor polisi sudah terdaftar',
            'seat_capacity.required' => 'Kapasitas penumpang wajib diisi',
            'seat_capacity.min'      => 'Kapasitas minimal 1 orang',
            'status.required'        => 'Status wajib dipilih',
            'status.in'              => 'Status tidak valid',
            'odometer_km.required'   => 'Odometer wajib diisi',
            'odometer_km.min'        => 'Odometer tidak boleh negatif',
        ]);

        DB::beginTransaction();
        try {
            Vehicle::create($validated);
            DB::commit();

            return redirect()
                ->route('admin.vehicles.index')
                ->with('success', 'Kendaraan berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating vehicle', ['error' => $e->getMessage()]);
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat menambah kendaraan.');
        }
    }

    public function show(Vehicle $vehicle)
    {
        if (!$this->getAuthUser()->isAdminGA()) {
            abort(403, 'Akses ditolak.');
        }

        $vehicle->load([
            'assignments.loanRequest.requester',
            'assignments.loanRequest.unit',
            'currentAssignment.loanRequest.requester',
        ]);

        $stats = [
            'total_loans'      => $vehicle->assignments()->count(),
            'is_active_loan'   => $vehicle->currentAssignment !== null,
            'current_borrower' => $vehicle->currentAssignment?->loanRequest?->requester?->full_name ?? '-',
        ];

        return view('admin.vehicles.show', compact('vehicle', 'stats'));
    }

    public function edit(Vehicle $vehicle)
    {
        if (!$this->getAuthUser()->isAdminGA()) {
            abort(403, 'Akses ditolak.');
        }

        return view('admin.vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        if (!$this->getAuthUser()->isAdminGA()) {
            abort(403, 'Akses ditolak.');
        }

        // ✅ Kolom sesuai DB — HAPUS color, year, fuel_level
        $validated = $request->validate([
            'unit_code'     => 'nullable|string|max:50|unique:vehicles,unit_code,' . $vehicle->id,
            'brand'         => 'required|string|max:50',
            'model'         => 'required|string|max:100',
            'plate_no'      => 'required|string|max:20|unique:vehicles,plate_no,' . $vehicle->id,
            'seat_capacity' => 'required|integer|min:1|max:50',
            'status'        => 'required|in:available,in_use,maintenance,retired', // ✅ retired
            'odometer_km'   => 'required|integer|min:0',
            'notes'         => 'nullable|string|max:1000',
        ], [
            'unit_code.unique'       => 'Kode unit sudah digunakan',
            'brand.required'         => 'Merek wajib diisi',
            'model.required'         => 'Model wajib diisi',
            'plate_no.required'      => 'Nomor polisi wajib diisi',
            'plate_no.unique'        => 'Nomor polisi sudah terdaftar',
            'seat_capacity.required' => 'Kapasitas penumpang wajib diisi',
            'status.required'        => 'Status wajib dipilih',
            'status.in'              => 'Status tidak valid',
            'odometer_km.required'   => 'Odometer wajib diisi',
        ]);

        DB::beginTransaction();
        try {
            $vehicle->update($validated);
            DB::commit();

            return redirect()
                ->route('admin.vehicles.index')
                ->with('success', 'Kendaraan berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating vehicle', [
                'error'      => $e->getMessage(),
                'vehicle_id' => $vehicle->id,
            ]);
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui kendaraan.');
        }
    }

    public function destroy(Vehicle $vehicle)
    {
        if (!$this->getAuthUser()->isAdminGA()) {
            abort(403, 'Akses ditolak.');
        }

        if ($vehicle->isInUse()) {
            return back()->with('error', 'Tidak dapat menghapus kendaraan yang sedang digunakan!');
        }

        if ($vehicle->assignments()->exists()) {
            // ✅ FIX: pesan pakai 'retired' bukan 'unavailable'
            return back()->with('error', 'Tidak dapat menghapus kendaraan yang memiliki riwayat peminjaman! Ubah status menjadi Tidak Aktif (retired).');
        }

        DB::beginTransaction();
        try {
            $vehicle->delete();
            DB::commit();

            return redirect()
                ->route('admin.vehicles.index')
                ->with('success', 'Kendaraan berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting vehicle', [
                'error'      => $e->getMessage(),
                'vehicle_id' => $vehicle->id,
            ]);
            return back()->with('error', 'Terjadi kesalahan saat menghapus kendaraan.');
        }
    }

    // ✅ AJAX: Check plate_no duplikat
    public function checkPlateNo(Request $request)
    {
        $plateNo   = $request->input('plate_no');
        $vehicleId = $request->input('vehicle_id');

        $exists = Vehicle::where('plate_no', $plateNo)
            ->when($vehicleId, fn($q) => $q->where('id', '!=', $vehicleId))
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    // ✅ TAMBAH: AJAX check unit_code duplikat — unit_code ADA di DB
    public function checkUnitCode(Request $request)
    {
        $unitCode  = $request->input('unit_code');
        $vehicleId = $request->input('vehicle_id');

        $exists = Vehicle::where('unit_code', $unitCode)
            ->when($vehicleId, fn($q) => $q->where('id', '!=', $vehicleId))
            ->exists();

        return response()->json(['exists' => $exists]);
    }
}
