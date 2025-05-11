<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Atur default timezone untuk PHP
        date_default_timezone_set('Asia/Jakarta');
        
        // Atur default timezone untuk semua instance Carbon
        Carbon::setLocale('id');
        Carbon::setToStringFormat('Y-m-d H:i:s');

        // Tambahkan macro untuk memastikan timezone konsisten
        Carbon::macro('toFormattedDatetime', function () {
            return $this->copy()->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s');
        });
        
        // Atur default timezone untuk tampilan diffForHumans
        Carbon::macro('toDiffForHumans', function () {
            return $this->copy()->setTimezone('Asia/Jakarta')->diffForHumans();
        });
    }
}