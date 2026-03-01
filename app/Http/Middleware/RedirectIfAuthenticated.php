<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {

                // ✅ FIX BUG 2: Pakai guard yang sesuai
                 /** @var \App\Models\User $user */ 
                $user = Auth::guard($guard)->user();

                // ✅ FIX BUG 1: Cek is_active sebelum redirect
                if (!$user->is_active) {
                    Auth::guard($guard)->logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect()->route('login')
                        ->with('error', 'Akun Anda telah dinonaktifkan. Silakan hubungi Admin GA.');
                }

                // ✅ FIX BUG 3: Pakai getDashboardRoute() dari User model
                // Lebih aman daripada hardcode route names
                return redirect()->route($user->getDashboardRoute());
            }
        }

        return $next($request);
    }
}
