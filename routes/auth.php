<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

// Guest routes (hanya bisa diakses saat belum login)
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

// Auth routes (hanya bisa diakses saat sudah login)
Route::middleware('auth')->group(function () {
    Route::match(['get', 'post'], 'logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
