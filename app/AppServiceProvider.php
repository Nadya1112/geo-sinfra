<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// 1. Pastikan kedua model dan observer ini di-import di atas
use App\Models\Infrastruktur;
use App\Observers\InfrastrukturObserver;

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
        // 2. Daftarkan observer di sini
        Infrastruktur::observe(InfrastrukturObserver::class);
    }
}