<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Closure;

class Authenticate extends Middleware
{
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }

    // Override metode handle
    public function handle($request, Closure $next, ...$guards)
    {
        if (empty($guards)) {
            $guards = [null];
        }

        // Cek secara eksplisit untuk setiap guard
        if (in_array('admin', $guards) && Auth::guard('admin')->check()) {
            return $next($request);
        } elseif (in_array('mahasiswa', $guards) && Auth::guard('mahasiswa')->check()) {
            return $next($request);
        } elseif (in_array('dosen', $guards) && Auth::guard('dosen')->check()) {
            return $next($request);
        }

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return $next($request);
            }
        }

        return redirect()->route('login');
    }
}