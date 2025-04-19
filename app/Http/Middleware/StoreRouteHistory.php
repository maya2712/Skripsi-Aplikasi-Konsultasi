<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StoreRouteHistory
{
    public function handle(Request $request, Closure $next)
    {
        // Jalankan request dulu
        $response = $next($request);
        
        // Simpan URL saat ini ke dalam history hanya jika request adalah GET
        if ($request->method() == 'GET' && !$request->ajax()) {
            // Ambil stack yang sudah ada atau inisialisasi array kosong
            $routeStack = session()->get('routeStack', []);
            
            // Tambahkan URL saat ini ke stack
            $currentUrl = $request->fullUrl();
            
            // Hindari duplikasi URL yang sama berturut-turut
            if (empty($routeStack) || end($routeStack) !== $currentUrl) {
                // Jika ini adalah route /back, jangan tambahkan ke stack
                if (!str_contains($currentUrl, '/back')) {
                    $routeStack[] = $currentUrl;
                    
                    // Batasi jumlah route yang disimpan (opsional)
                    if (count($routeStack) > 10) {
                        array_shift($routeStack);
                    }
                    
                    session()->put('routeStack', $routeStack);
                }
            }
        }
        
        return $response;
    }
}