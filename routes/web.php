<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Pintu masuk /dashboard: me-redirect ke dashboard sesuai role
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

// Route group per role
Route::middleware(['auth', 'role:admin'])->prefix('dashboard/admin')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'admin'])->name('admin');
});

Route::middleware(['auth', 'role:kepala_sekolah'])->prefix('dashboard/kepala-sekolah')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'kepalaSekolah'])->name('kepala-sekolah');
});

Route::middleware(['auth', 'role:tata_usaha'])->prefix('dashboard/tata-usaha')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'tataUsaha'])->name('tata-usaha');
});

Route::middleware(['auth', 'role:guru'])->prefix('dashboard/guru')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'guru'])->name('guru');
});

Route::middleware(['auth', 'role:tenaga_pendidik'])->prefix('dashboard/tenaga-pendidik')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'tenagaPendidik'])->name('tenaga-pendidik');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
