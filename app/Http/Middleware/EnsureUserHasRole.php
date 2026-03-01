<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Universal role checker
     * Usage di route: ->middleware('role:admin_ga,kepala_departemen')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        // Belum login
        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // ✅ FIX BUG 6: Cek is_active
        if (!$user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('error', 'Akun Anda telah dinonaktifkan. Silakan hubungi Admin GA.');
        }

        // Cek role
        if (!in_array($user->role, $roles)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Akses ditolak.'], 403);
            }

            // ✅ Redirect ke home route sesuai role (bukan abort)
            return redirect()->route($user->getDashboardRoute())
                ->with('error', 'Akses ditolak. Anda tidak memiliki izin mengakses halaman ini.');
        }

        return $next($request);
    }
}
