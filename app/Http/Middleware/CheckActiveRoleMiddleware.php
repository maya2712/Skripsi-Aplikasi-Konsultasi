<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckActiveRoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (session('active_role') !== $role) {
            return redirect('/dashboardpesandosen')
                ->with('error', 'Anda perlu beralih ke mode ' . ucfirst($role) . ' untuk mengakses halaman ini');
        }
        
        return $next($request);
    }
}