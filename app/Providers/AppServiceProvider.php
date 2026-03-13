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
        config(['app.locale' => 'id']);
        \Carbon\Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');

        \App\Models\PelayananKeprotokolan::observe(\App\Observers\PelayananKeprotokolanObserver::class);
        \App\Models\Persidangan::observe(\App\Observers\PersidanganObserver::class);
        \App\Models\AdministrasiPerjalananDinas::observe(\App\Observers\AdmPerjalananDinasObserver::class);
        \App\Models\KunjunganKerja::observe(\App\Observers\KunjunganKerjaObserver::class);

        \Illuminate\Support\Facades\View::composer('layouts.sidebar', \App\Http\View\Composers\SidebarComposer::class);
    }
}
