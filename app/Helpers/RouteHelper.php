<?php

namespace App\Helpers;

class RouteHelper
{
    public static function getPreviousUrl($default = null)
    {
        $routeStack = session()->get('routeStack', []);
        
        // Jika ada minimal 2 URL dalam stack, ambil URL sebelumnya
        if (count($routeStack) > 1) {
            return array_slice($routeStack, -2, 1)[0];
        }
        
        // Jika tidak ada history, gunakan URL default
        return $default ?? url('/');
    }
}