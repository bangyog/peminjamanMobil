<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;

class UnitController extends Controller
{
    private function getAuthUser(): User
    {
        /** @var User $user */
        $user = Auth::user();
        return $user;
    }

    public function index(Request $request)
    {
        $user = $this->getAuthUser();
        if (!$user->isAdminGA()) {
            abort(403, 'Akses ditolak.');
        }

        $query = Unit::with(['kepalaDepartemen'])
            ->withCount(['users', 'loanRequests'])
            // ✅ Sembunyikan unit GA — unit internal admin, bukan departemen
            ->whereDoesntHave('users', function ($q) {
                $q->where('role', 'admin_ga');
            });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $units = $query->orderBy('name')->paginate(10)->withQueryString();

        // ✅ Stats juga exclude unit GA
        $departemenUnits = Unit::whereDoesntHave('users', fn($q) => $q->where('role', 'admin_ga'));

        $stats = [
            'total'       => (clone $departemenUnits)->count(),
            'active'      => (clone $departemenUnits)->where('is_active', true)->count(),
            'inactive'    => (clone $departemenUnits)->where('is_active', false)->count(),
            'with_kepala' => (clone $departemenUnits)->whereNotNull('kepala_departemen_id')->count(),
        ];

        return view('admin.units.index', compact('units', 'stats'));
    }


    public function create()
    {
        $this->getAuthUser()->isAdminGA() ?: abort(403);
        return view('admin.units.create');
    }

    public function store(Request $request)
    {
        $this->getAuthUser()->isAdminGA() ?: abort(403);

        // ✅ Kepala WAJIB diisi — tidak ada opsi skip
        $validated = $request->validate([
            'name'                         => 'required|string|max:100|unique:units,name',
            'is_active'                    => 'nullable|boolean',
            // Kepala wajib
            'kepala_full_name'             => 'required|string|max:150',
            'kepala_email'                 => 'required|email|max:150|unique:users,email',
            'kepala_phone'                 => 'nullable|string|max:30|unique:users,phone',
            'kepala_password'              => ['required', 'confirmed', Rules\Password::defaults()],
            'kepala_password_confirmation' => 'required',
        ], [
            'name.required'                => 'Nama unit harus diisi',
            'name.unique'                  => 'Nama unit sudah digunakan',
            'kepala_full_name.required'    => 'Nama kepala departemen harus diisi',
            'kepala_email.required'        => 'Email kepala departemen harus diisi',
            'kepala_email.unique'          => 'Email sudah digunakan oleh user lain',
            'kepala_phone.unique'          => 'Nomor HP sudah digunakan',
            'kepala_password.required'     => 'Password kepala departemen harus diisi',
            'kepala_password_confirmation.required' => 'Konfirmasi password harus diisi',
        ]);

        DB::beginTransaction();
        try {
            // 1. Buat unit dulu
            $unit = Unit::create([
                'name'      => $validated['name'],
                'is_active' => $request->boolean('is_active', true),
            ]);

            // 2. Buat akun kepala departemen — SELALU
            $kepala = User::create([
                'unit_id'   => $unit->id,
                'full_name' => $validated['kepala_full_name'],
                'email'     => $validated['kepala_email'],
                'phone'     => $validated['kepala_phone'] ?? null,
                'password'  => Hash::make($validated['kepala_password']),
                'role'      => 'kepala_departemen',
                'is_active' => true,
            ]);

            // 3. Set kepala_departemen_id di unit
            $unit->update(['kepala_departemen_id' => $kepala->id]);

            DB::commit();

            return redirect()->route('admin.units.index')
                ->with('success', "Unit {$unit->name} & akun Kepala Departemen {$kepala->full_name} berhasil dibuat!");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating unit', ['error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menambah unit.');
        }
    }

    public function show(Unit $unit)
    {
        $user = $this->getAuthUser();
        if (!$user->isAdminGA()) {
            abort(403, 'Akses ditolak.');
        }

        $unit->load([
            'kepalaDepartemen',
            'users' => fn($q) => $q->orderBy('full_name'),
        ]);

        $stats = [
            'total_users'    => $unit->users()->count(),
            'active_users'   => $unit->users()->where('is_active', true)->count(),
            'total_loans'    => $unit->loanRequests()->count(),
            'pending_loans'  => $unit->loanRequests()
                ->whereIn('status', ['submitted', 'approved_kepala'])->count(),
            'active_loans'   => $unit->loanRequests()
                ->whereIn('status', ['approved_ga', 'assigned', 'in_use'])->count(),
            'returned_loans' => $unit->loanRequests()
                ->where('status', 'returned')->count(),
            'rejected_loans' => $unit->loanRequests()
                ->where('status', 'rejected')->count(),
        ];

        return view('admin.units.show', compact('unit', 'stats'));
    }

    public function edit(Unit $unit)
    {
        $this->getAuthUser()->isAdminGA() ?: abort(403);

        $availableKepala = User::where('role', 'kepala_departemen')
            ->where('is_active', true)
            ->where(function ($q) use ($unit) {
                $q->whereDoesntHave('unitAsKepala')
                    ->orWhere('id', $unit->kepala_departemen_id);
            })
            ->orderBy('full_name')
            ->get();

        return view('admin.units.edit', compact('unit', 'availableKepala'));
    }

    public function update(Request $request, Unit $unit)
    {
        $this->getAuthUser()->isAdminGA() ?: abort(403);

        $validated = $request->validate([
            'name'                 => 'required|string|max:100|unique:units,name,' . $unit->id,
            'kepala_departemen_id' => 'nullable|exists:users,id',
            'is_active'            => 'nullable|boolean',
        ], [
            'name.required' => 'Nama unit harus diisi',
            'name.unique'   => 'Nama unit sudah digunakan unit lain',
        ]);

        if (!empty($validated['kepala_departemen_id'])) {
            $kepala = User::find($validated['kepala_departemen_id']);

            if ($kepala->role !== 'kepala_departemen') {
                return back()->withInput()
                    ->with('error', 'User yang dipilih harus memiliki role Kepala Departemen!');
            }

            if ($kepala->unitAsKepala && $kepala->unitAsKepala->id !== $unit->id) {
                return back()->withInput()
                    ->with('error', 'User ini sudah menjadi kepala di unit lain!');
            }
        }

        DB::beginTransaction();
        try {
            $oldKepalaId = (int) $unit->kepala_departemen_id;
            $newKepalaId = (int) ($validated['kepala_departemen_id'] ?? 0);

            $unit->update([
                'name'                 => $validated['name'],
                'kepala_departemen_id' => $validated['kepala_departemen_id'] ?? null,
                'is_active'            => $request->boolean('is_active', $unit->is_active),
            ]);

            // Update unit_id kepala baru jika berbeda
            if ($newKepalaId && $oldKepalaId !== $newKepalaId) {
                User::where('id', $newKepalaId)->update(['unit_id' => $unit->id]);
            }

            DB::commit();

            return redirect()->route('admin.units.index')
                ->with('success', 'Unit berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating unit', ['error' => $e->getMessage(), 'unit_id' => $unit->id]);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui unit.');
        }
    }

    public function destroy(Unit $unit)
    {
        $this->getAuthUser()->isAdminGA() ?: abort(403);

        if ($unit->users()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus unit yang memiliki user! Nonaktifkan saja.');
        }

        if ($unit->loanRequests()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus unit yang memiliki riwayat peminjaman! Nonaktifkan saja.');
        }

        DB::beginTransaction();
        try {
            $unit->delete();
            DB::commit();
            return redirect()->route('admin.units.index')->with('success', 'Unit berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting unit', ['error' => $e->getMessage(), 'unit_id' => $unit->id]);
            return back()->with('error', 'Terjadi kesalahan saat menghapus unit.');
        }
    }

    public function checkName(Request $request)
    {
        $this->getAuthUser()->isAdminGA() ?: abort(403);

        $exists = Unit::where('name', $request->input('name'))
            ->when($request->input('unit_id'), fn($q) => $q->where('id', '!=', $request->input('unit_id')))
            ->exists();

        return response()->json(['exists' => $exists]);
    }
}
