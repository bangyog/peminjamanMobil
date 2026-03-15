<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();

        $query = User::with('unit');

        if ($currentUser->role === 'admin_ga') {
            $query->where('role', 'kepala_departemen');
        } elseif (in_array($currentUser->role, ['kepala_departemen', 'admin_hr'])) {
            $query->where('unit_id', $currentUser->unit_id)
                  ->where('role', 'user');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role') && $currentUser->role === 'admin_ga') {
            $query->where('role', $request->role);
        }

        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        $users = $query->orderBy('full_name')->paginate(15)->withQueryString();

        if (in_array($currentUser->role, ['kepala_departemen', 'admin_hr'])) {
            $units = Unit::where('id', $currentUser->unit_id)->get();
        } else {
            $units = Unit::orderBy('name')->get();
        }

        return view('admin.users.index', compact('users', 'units'));
    }

    public function create()
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();

        if ($currentUser->role === 'admin_ga') {
            $availableRoles = [
                'kepala_departemen' => '👔 Kepala Departemen',
            ];
            $units = Unit::orderBy('name')->get();

        } elseif (in_array($currentUser->role, ['kepala_departemen', 'admin_hr'])) {
            $availableRoles = [
                'user' => '👤 User',
            ];
            $units = Unit::where('id', $currentUser->unit_id)->get();

        } else {
            abort(403, 'Anda tidak memiliki izin untuk menambah user.');
        }

        return view('admin.users.create', compact('units', 'availableRoles'));
    }

    public function store(Request $request)
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();

        if (!in_array($currentUser->role, ['admin_ga', 'kepala_departemen', 'admin_hr'])) {
            abort(403, 'Anda tidak memiliki izin untuk menambah user.');
        }

        $validated = $request->validate([
            'unit_id'   => 'required|integer|exists:units,id', // ✅ tambah integer
            'full_name' => 'required|string|max:150',          // ✅ fix: 100 → 150
            'email'     => 'required|string|email|max:150|unique:users,email', // ✅ fix: 100 → 150
            'phone'     => 'nullable|string|max:30|unique:users,phone',        // ✅ fix: 20 → 30
            'password'  => ['required', 'confirmed', Rules\Password::defaults()],
            'role'      => 'required|in:kepala_departemen,user',
            'is_active' => 'required|boolean',
        ]);

        // ✅ Cast unit_id ke int agar perbandingan aman
        $unitId = (int) $validated['unit_id'];

        if ($currentUser->role === 'admin_ga') {
            if ($validated['role'] !== 'kepala_departemen') {
                return back()->withInput()
                    ->with('error', 'Admin GA hanya bisa membuat Kepala Departemen!');
            }

            $existingKepala = User::where('unit_id', $unitId)
                ->where('role', 'kepala_departemen')
                ->exists();

            if ($existingKepala) {
                return back()->withInput()
                    ->with('error', 'Unit ini sudah memiliki Kepala Departemen!');
            }

        } elseif (in_array($currentUser->role, ['kepala_departemen', 'admin_hr'])) {
            if ($validated['role'] !== 'user') {
                return back()->withInput()
                    ->with('error', 'Anda hanya bisa membuat User biasa!');
            }

            // ✅ FIX BUG #1: cast ke int sebelum compare
            if ($unitId !== (int) $currentUser->unit_id) {
                return back()->withInput()
                    ->with('error', 'Anda hanya dapat menambah user di unit Anda sendiri!');
            }
        }

        DB::beginTransaction();
        try {
            $user = User::create([
                'unit_id'   => $unitId,
                'full_name' => $validated['full_name'],
                'email'     => $validated['email'],
                'phone'     => $validated['phone'],
                'password'  => Hash::make($validated['password']),
                'role'      => $validated['role'],
                'is_active' => $validated['is_active'],
            ]);

            if ($validated['role'] === 'kepala_departemen') {
                Unit::where('id', $unitId)
                    ->update(['kepala_departemen_id' => $user->id]);
            }

            DB::commit();

            Log::info('User created', [
                'created_by'      => $currentUser->id,
                'created_by_role' => $currentUser->role,
                'new_user_id'     => $user->id,
                'new_user_role'   => $user->role,
            ]);

            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating user: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat menambah user.');
        }
    }

    public function show(User $user)
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();

        if ($currentUser->role === 'admin_ga') {
            if ($user->role !== 'kepala_departemen') {
                abort(403, 'Anda hanya bisa melihat Kepala Departemen.');
            }
        } elseif (in_array($currentUser->role, ['kepala_departemen', 'admin_hr'])) {
            // ✅ FIX BUG #2: cast ke int
            if ((int)$user->unit_id !== (int)$currentUser->unit_id || $user->role !== 'user') {
                abort(403, 'Anda tidak memiliki izin untuk melihat user ini.');
            }
        } else {
            abort(403);
        }

        $user->load(['unit', 'loanRequests' => function($query) {
            $query->latest()->take(10);
        }]);

        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();

        if ($currentUser->role === 'admin_ga') {
            if ($user->role !== 'kepala_departemen') {
                abort(403, 'Anda hanya bisa mengedit Kepala Departemen.');
            }
            $availableRoles = ['kepala_departemen' => '👔 Kepala Departemen'];
            $units = Unit::orderBy('name')->get();

        } elseif (in_array($currentUser->role, ['kepala_departemen', 'admin_hr'])) {
            // ✅ FIX BUG #2: cast ke int
            if ((int)$user->unit_id !== (int)$currentUser->unit_id || $user->role !== 'user') {
                abort(403, 'Anda tidak memiliki izin untuk mengedit user ini.');
            }
            $availableRoles = ['user' => '👤 User'];
            $units = Unit::where('id', $currentUser->unit_id)->get();

        } else {
            abort(403);
        }

        return view('admin.users.edit', compact('user', 'units', 'availableRoles'));
    }

    public function update(Request $request, User $user)
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();

        if ($currentUser->role === 'admin_ga') {
            if ($user->role !== 'kepala_departemen') {
                abort(403, 'Anda hanya bisa mengedit Kepala Departemen.');
            }
        } elseif (in_array($currentUser->role, ['kepala_departemen', 'admin_hr'])) {
            // ✅ FIX BUG #2: cast ke int
            if ((int)$user->unit_id !== (int)$currentUser->unit_id || $user->role !== 'user') {
                abort(403, 'Anda tidak memiliki izin untuk mengedit user ini.');
            }
        } else {
            abort(403);
        }

        $validated = $request->validate([
            'unit_id'   => 'required|integer|exists:units,id',                          // ✅ tambah integer
            'full_name' => 'required|string|max:150',                                   // ✅ fix: 100 → 150
            'email'     => 'required|string|email|max:150|unique:users,email,' . $user->id, // ✅ fix: 100 → 150
            'phone'     => 'nullable|string|max:30|unique:users,phone,' . $user->id,    // ✅ fix: 20 → 30
            'password'  => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role'      => 'required|in:kepala_departemen,user',
            'is_active' => 'required|boolean',
        ]);

        // ✅ Cast unit_id ke int agar perbandingan aman
        $unitId = (int) $validated['unit_id'];

        if ($currentUser->role === 'admin_ga') {
            if ($validated['role'] !== 'kepala_departemen') {
                return back()->withInput()
                    ->with('error', 'Admin GA hanya bisa mengelola Kepala Departemen!');
            }

            // ✅ FIX BUG #2: cast ke int
            if ($unitId !== (int) $user->unit_id) {
                $existingKepala = User::where('unit_id', $unitId)
                    ->where('role', 'kepala_departemen')
                    ->where('id', '!=', $user->id)
                    ->exists();

                if ($existingKepala) {
                    return back()->withInput()
                        ->with('error', 'Unit tujuan sudah memiliki Kepala Departemen!');
                }
            }

        } elseif (in_array($currentUser->role, ['kepala_departemen', 'admin_hr'])) {
            if ($validated['role'] !== 'user') {
                return back()->withInput()
                    ->with('error', 'Anda hanya bisa mengelola User biasa!');
            }

            // ✅ FIX BUG #1: cast ke int
            if ($unitId !== (int) $currentUser->unit_id) {
                return back()->withInput()
                    ->with('error', 'Anda hanya dapat mengelola user di unit Anda sendiri!');
            }
        }

        DB::beginTransaction();
        try {
            $oldUnitId = (int) $user->unit_id; // ✅ simpan sebagai int

            $updateData = [
                'unit_id'   => $unitId,
                'full_name' => $validated['full_name'],
                'email'     => $validated['email'],
                'phone'     => $validated['phone'],
                'role'      => $validated['role'],
                'is_active' => $validated['is_active'],
            ];

            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

            // ✅ FIX BUG #6: gunakan $validated['role'] bukan $user->role setelah update
            // ✅ FIX BUG #2: $oldUnitId sudah int, $unitId sudah int
            if ($validated['role'] === 'kepala_departemen' && $oldUnitId !== $unitId) {
                Unit::where('id', $oldUnitId)->update(['kepala_departemen_id' => null]);
                Unit::where('id', $unitId)->update(['kepala_departemen_id' => $user->id]);
            }

            DB::commit();

            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating user: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui user.');
        }
    }

    public function destroy(User $user)
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();

        if ($currentUser->role === 'admin_ga') {
            if ($user->role !== 'kepala_departemen') {
                abort(403, 'Anda hanya bisa menghapus Kepala Departemen.');
            }
        } elseif (in_array($currentUser->role, ['kepala_departemen', 'admin_hr'])) {
            // ✅ FIX BUG #2: cast ke int
            if ((int)$user->unit_id !== (int)$currentUser->unit_id || $user->role !== 'user') {
                abort(403, 'Anda tidak memiliki izin untuk menghapus user ini.');
            }
        } else {
            abort(403);
        }

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri!');
        }

        if ($user->loanRequests()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus user yang memiliki riwayat peminjaman! Nonaktifkan saja.');
        }

        DB::beginTransaction();
        try {
            if ($user->role === 'kepala_departemen') {
                Unit::where('kepala_departemen_id', $user->id)
                    ->update(['kepala_departemen_id' => null]);
            }

            $user->delete();
            DB::commit();

            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting user: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus user.');
        }
    }

    public function checkEmail(Request $request)
    {
        $exists = User::where('email', $request->input('email'))
            ->when($request->input('user_id'), fn($q) => $q->where('id', '!=', $request->input('user_id')))
            ->exists();
        return response()->json(['exists' => $exists]);
    }

    public function checkPhone(Request $request)
    {
        if (empty($request->input('phone'))) {
            return response()->json(['exists' => false]);
        }
        $exists = User::where('phone', $request->input('phone'))
            ->when($request->input('user_id'), fn($q) => $q->where('id', '!=', $request->input('user_id')))
            ->exists();
        return response()->json(['exists' => $exists]);
    }

    public function checkName(Request $request)
    {
        if (empty($request->input('full_name'))) {
            return response()->json(['exists' => false]);
        }
        $exists = User::where('full_name', $request->input('full_name'))
            ->when($request->input('user_id'), fn($q) => $q->where('id', '!=', $request->input('user_id')))
            ->exists();
        return response()->json(['exists' => $exists]);
    }
}
