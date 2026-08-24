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
    public function boot(\Illuminate\Http\Request $request): void
    {
        if (!app()->runningInConsole()) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        \App\Models\Infrastruktur::observe(\App\Observers\InfrastrukturObserver::class);

        // Share jumlah laporan menunggu ke semua view admin
        View::composer(['admin.*'], function ($view) {
            $user = auth()->user();
            $query = LaporanWarga::where('status', 'Menunggu');
            
            if ($user && $user->last_read_laporan_at) {
                $query->where('created_at', '>', $user->last_read_laporan_at);
            }
            
            $laporanMenungguCount = $query->count();
            $view->with('laporanMenungguCount', $laporanMenungguCount);
        });
    }
}
