<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Auth;

class EnsurePersonnelIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->role === 'personel') {
            $personnel = \App\Models\Personnel::where('user_id', $user->id)->first();
            
            if ($personnel && !$personnel->is_active) {
                return redirect()->route('personnel.pending');
            }
        }

        return $next($request);
    }
}
