<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DisposisiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\SuratMasukController;
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
    Route::get('/akun', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/akun', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::patch('/akun/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::patch('/akun/{user}/status', [AdminController::class, 'toggleStatus'])->name('admin.users.toggle-status');
    Route::patch('/akun/{user}/reset-password', [AdminController::class, 'resetPassword'])->name('admin.users.reset-password');
    Route::get('/permintaan-pendaftaran', [AdminController::class, 'registrations'])->name('admin.registrations');
    Route::patch('/permintaan-pendaftaran/{user}/approve', [AdminController::class, 'approveRegistration'])->name('admin.registrations.approve');
    Route::patch('/permintaan-pendaftaran/{user}/reject', [AdminController::class, 'rejectRegistration'])->name('admin.registrations.reject');
    Route::get('/audit', [AdminController::class, 'audit'])->name('admin.audit');
});

Route::middleware(['auth', 'role:kepala_sekolah'])->prefix('dashboard/kepala-sekolah')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'kepalaSekolah'])->name('kepala-sekolah');
    Route::get('/analytics', [DashboardController::class, 'kepalaSekolahAnalytics'])->name('kepala-sekolah.analytics');
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

// Modul Surat Masuk & Alur Disposisi
Route::middleware('auth')->group(function () {
    Route::get('/surat-masuk', [SuratMasukController::class, 'index'])->name('surat-masuk.index');
    Route::post('/surat-masuk', [SuratMasukController::class, 'store'])->name('surat-masuk.store');
    Route::get('/surat-masuk/{suratMasuk}', [SuratMasukController::class, 'show'])->name('surat-masuk.show');
    Route::get('/surat-masuk/{suratMasuk}/unduh', [SuratMasukController::class, 'download'])->name('surat-masuk.download');
    Route::get('/surat-masuk/{suratMasuk}/lembar-disposisi', [SuratMasukController::class, 'lembarDisposisi'])->name('surat-masuk.lembar-disposisi');

    // Endpoint Aksi Disposisi
    Route::post('/surat-masuk/{suratMasuk}/disposisi', [DisposisiController::class, 'store'])->name('disposisi.store');
    Route::patch('/disposisi/{disposisi}', [DisposisiController::class, 'update'])->name('disposisi.update');

    // Modul Surat Keluar
    Route::get('/surat-keluar', [SuratKeluarController::class, 'index'])->name('surat-keluar.index');
    Route::post('/surat-keluar', [SuratKeluarController::class, 'store'])->name('surat-keluar.store');
    Route::get('/surat-keluar/{suratKeluar}', [SuratKeluarController::class, 'show'])->name('surat-keluar.show');
    Route::patch('/surat-keluar/{suratKeluar}/approve', [SuratKeluarController::class, 'approve'])->name('surat-keluar.approve');
    Route::get('/surat-keluar/{suratKeluar}/unduh', [SuratKeluarController::class, 'download'])->name('surat-keluar.download');

    // Profil Pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
