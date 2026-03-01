<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            // ✅ FIX BUG 5: Sesuaikan dengan field 'name' di database
            'name'  => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:100',
                        'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20',
                        'unique:users,phone,' . $user->id],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return Redirect::route('profile.edit')
            ->with('success', 'Password berhasil diperbarui!');
    }

    /**
     * Deactivate the user's account (BUKAN hard delete!)
     * ✅ FIX BUG 2: Soft deactivate, bukan hard delete
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // ✅ FIX BUG 4: Cegah admin/kepala hapus akun
        if ($user->isAdminGA()) {
            return Redirect::route('profile.edit')
                ->withErrors([
                    'password' => 'Akun Admin GA tidak dapat dinonaktifkan melalui profil.'
                ], 'userDeletion');
        }

        if ($user->isKepalaDepartemen()) {
            return Redirect::route('profile.edit')
                ->withErrors([
                    'password' => 'Akun Kepala Departemen tidak dapat dinonaktifkan. Hubungi Admin GA.'
                ], 'userDeletion');
        }

        // ✅ FIX BUG 2: Deactivate saja, jangan hard delete
        Auth::logout();

        $user->update(['is_active' => false]);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/')->with('info', 'Akun Anda telah dinonaktifkan.');
    }
}
