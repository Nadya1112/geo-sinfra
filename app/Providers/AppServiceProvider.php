<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\LaporanWarga;

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
        \App\Models\Infrastruktur::observe(\App\Observers\InfrastrukturObserver::class);

        // Share jumlah laporan menunggu ke semua view admin
        View::composer(['admin.*'], function ($view) {
            $laporanMenungguCount = LaporanWarga::where('status', 'Menunggu')->count();
            $view->with('laporanMenungguCount', $laporanMenungguCount);
        });
    }
}
