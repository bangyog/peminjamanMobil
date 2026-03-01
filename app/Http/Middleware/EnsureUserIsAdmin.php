<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Hanya untuk Admin GA
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // ✅ FIX BUG 4: Tambah is_active check
        if (!$user || $user->role !== 'admin_ga' || !$user->is_active) {
            // ✅ FIX BUG 5: User-friendly response
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized.'], 403);
            }

            return redirect()->route('dashboard')
                ->with('error', 'Akses ditolak. Halaman ini hanya untuk Admin GA.');
        }

        return $next($request);
    }
}
