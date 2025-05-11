<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TimezoneMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Set timezone default untuk semua instance Carbon baru
        date_default_timezone_set('Asia/Jakarta');
        Carbon::setToStringFormat('Y-m-d H:i:s');
        
        // Lanjutkan request
        return $next($request);
    }
}