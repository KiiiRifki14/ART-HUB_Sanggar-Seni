<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Mengamankan route berdasarkan Enum role di tabel users (admin, personel, klien)
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        // Cek apakah role user saat ini ada di dalam array roles yang diizinkan route
        if (!in_array(Auth::user()->role, $roles)) {
            $userRole = Auth::user()->role;
            $redirectPath = match ($userRole) {
                'personnel' => '/personnel/dashboard',
                'klien' => '/klien/dashboard',
                default => '/dashboard',
            };

            return redirect($redirectPath)->with('error', 'Akses Ditolak: Anda tidak memiliki wewenang untuk memasuki area tersebut.');
        }

        return $next($request);
    }
}
