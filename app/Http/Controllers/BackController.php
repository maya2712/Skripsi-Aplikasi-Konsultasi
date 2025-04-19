<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BackController extends Controller
{
    public function back()
    {
        // Ambil stack route yang disimpan
        $routeStack = session()->get('routeStack', []);
        
        // Jika stack tidak kosong, ambil URL terakhir (sebelum URL saat ini)
        if (count($routeStack) >= 2) {
            // Hapus URL terakhir dari stack (URL saat ini)
            array_pop($routeStack);
            // Ambil URL sebelumnya
            $previousUrl = end($routeStack);
            // Update stack di session
            session()->put('routeStack', $routeStack);
            // Redirect ke URL sebelumnya
            return redirect($previousUrl);
        }
        
        // Jika tidak ada riwayat, redirect ke dashboard
        return redirect()->route('dashboard.pesandosen');
    }
}