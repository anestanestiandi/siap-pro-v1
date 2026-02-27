<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\PelayananKeprotokolan;
use App\Observers\PelayananKeprotokolanObserver;

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
        \App\Models\PelayananKeprotokolan::observe(\App\Observers\PelayananKeprotokolanObserver::class);
        \App\Models\Persidangan::observe(\App\Observers\PersidanganObserver::class);
        \App\Models\AdministrasiPerjalananDinas::observe(\App\Observers\AdmPerjalananDinasObserver::class);
        \App\Models\KunjunganKerja::observe(\App\Observers\KunjunganKerjaObserver::class);
    }
}
