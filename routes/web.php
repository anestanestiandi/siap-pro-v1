<?php

use App\Http\Controllers\PelayananKeprotokolanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — SIAP-PRO
|--------------------------------------------------------------------------
*/

// Redirect root ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authenticated routes (semua role)
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Pelayanan Keprotokolan
    Route::resource('pelayanan-keprotokolan', PelayananKeprotokolanController::class)->names([
        'index' => 'pelayanan-keprotokolan',
        'create' => 'pelayanan-keprotokolan.create',
        'store' => 'pelayanan-keprotokolan.store',
        'show' => 'pelayanan-keprotokolan.show',
        'edit' => 'pelayanan-keprotokolan.edit',
        'update' => 'pelayanan-keprotokolan.update',
        'destroy' => 'pelayanan-keprotokolan.destroy',
    ]);
    
    // Persidangan
    Route::resource('persidangan', \App\Http\Controllers\PersidanganController::class)->names([
        'index' => 'persidangan',
        'create' => 'persidangan.create',
        'store' => 'persidangan.store',
        'show' => 'persidangan.show',
        'edit' => 'persidangan.edit',
        'update' => 'persidangan.update',
        'destroy' => 'persidangan.destroy',
    ]);
    
    // Kunjungan Kerja
    Route::resource('kunjungan-kerja', \App\Http\Controllers\KunjunganKerjaController::class)->names([
        'index' => 'kunjungan-kerja',
        'create' => 'kunjungan-kerja.create',
        'store' => 'kunjungan-kerja.store',
        'show' => 'kunjungan-kerja.show',
        'edit' => 'kunjungan-kerja.edit',
        'update' => 'kunjungan-kerja.update',
        'destroy' => 'kunjungan-kerja.destroy',
    ]);

       // Administrasi Perjalanan Dinas
    Route::resource('administrasi-perjalanan-dinas', \App\Http\Controllers\AdmPerjalananDinasController::class)->names([
        'index' => 'administrasi-perjalanan-dinas',
        'create' => 'administrasi-perjalanan-dinas.create',
        'store' => 'administrasi-perjalanan-dinas.store',
        'show' => 'administrasi-perjalanan-dinas.show',
        'edit' => 'administrasi-perjalanan-dinas.edit',
        'update' => 'administrasi-perjalanan-dinas.update',
        'destroy' => 'administrasi-perjalanan-dinas.destroy',
    ]);
});

// Penugasan Protokol — super_admin & admin
Route::middleware(['auth', 'role:super_admin,admin'])->group(function () {
    Route::get('/penugasan-protokol', [\App\Http\Controllers\PenugasanProtokolController::class, 'index'])->name('penugasan-protokol');
    Route::get('/penugasan-protokol/{id}', [\App\Http\Controllers\PenugasanProtokolController::class, 'show'])->name('penugasan-protokol.show');
});

// Management User — super_admin only
Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::resource('management-user', \App\Http\Controllers\ManagementUserController::class);

    // Reset Password Routes
    Route::get('/management-user/{id}/reset-password', [\App\Http\Controllers\ManagementUserController::class, 'resetPasswordForm'])
        ->name('management-user.reset-password-form');
    Route::put('/management-user/{id}/reset-password', [\App\Http\Controllers\ManagementUserController::class, 'updatePassword'])
        ->name('management-user.update-password');
});

// History Log Activity — super_admin & admin only
Route::middleware(['auth', 'role:super_admin,admin'])->group(function () {
    Route::get('/history-log-activity', [\App\Http\Controllers\HistoryLogActivityController::class, 'index'])
        ->name('history-log-activity');
});

require __DIR__ . '/auth.php';
