<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\NormalAdminController;
use App\Http\Controllers\WelcomeController;

// ===========================
// HALAMAN UTAMA
// ===========================
Route::get('/', [WelcomeController::class, 'index']);

// ===========================
// AUTH
// ===========================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'loginProcess'])->name('login.process');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// (opsional) list kegiatan untuk publik / ajax
Route::get('/kegiatan/list', [NormalAdminController::class, 'list']);

// ===========================
// SUPERADMIN
// ===========================
Route::prefix('superadmin')
    ->middleware(['role:superadmin'])
    ->group(function () {
        Route::get('/', [SuperAdminController::class, 'index'])->name('superadmin.dashboard');

        // PROFIL
        Route::get('/profil', [SuperAdminController::class, 'profilIndex'])->name('superadmin.profil');
        Route::post('/profil/store', [SuperAdminController::class, 'profilStore'])->name('superadmin.profil.store');
        Route::post('/profil/update/{id}', [SuperAdminController::class, 'profilUpdate'])->name('superadmin.profil.update');
        Route::delete('/profil/delete/{id}', [SuperAdminController::class, 'profilDelete'])->name('superadmin.profil.delete');

        // VIDEO
        Route::post('/video/store', [SuperAdminController::class, 'videoStore'])->name('superadmin.video.store');
        Route::post('/video/update/{id}', [SuperAdminController::class, 'videoUpdate'])->name('superadmin.video.update');
        Route::delete('/video/delete/{id}', [SuperAdminController::class, 'videoDelete'])->name('superadmin.video.delete');

        // KEGIATAN
        Route::get('/kegiatan/list', [SuperAdminController::class, 'kegiatanList']);
        Route::get('/kegiatan/table', [SuperAdminController::class, 'kegiatanTable']);
        Route::get('/kegiatan/{id}', [SuperAdminController::class, 'kegiatanDetail']);
        Route::post('/kegiatan/store', [SuperAdminController::class, 'kegiatanStore']);
        Route::post('/kegiatan/{id}', [SuperAdminController::class, 'kegiatanUpdate']);
        Route::delete('/kegiatan/{id}', [SuperAdminController::class, 'kegiatanDelete']);

        // RUNNING TEXT
        Route::post('/runningtext/store', [SuperAdminController::class, 'runningtextStore'])->name('superadmin.runningtext.store');
        Route::post('/runningtext/update/{id}', [SuperAdminController::class, 'runningtextUpdate'])->name('superadmin.runningtext.update');
        Route::delete('/runningtext/delete/{id}', [SuperAdminController::class, 'runningtextDelete'])->name('superadmin.runningtext.delete');

        // KELOLA ADMIN
        Route::get('/normaladmin', [SuperAdminController::class, 'normalAdminIndex'])->name('superadmin.normaladmin');
        Route::get('/normaladmin/list', [SuperAdminController::class, 'normalAdminList']);
        Route::post('/normaladmin/store', [SuperAdminController::class, 'normalAdminStore'])->name('superadmin.normaladmin.store');
        Route::post('/normaladmin/update/{id}', [SuperAdminController::class, 'normalAdminUpdate'])->name('superadmin.normaladmin.update');
        Route::post('/normaladmin/delete/{id}', [SuperAdminController::class, 'normalAdminDelete'])->name('superadmin.normaladmin.delete');
    });

// ===========================
// NORMAL ADMIN
// ===========================
Route::prefix('admin')
    ->middleware(['role:normaladmin'])
    ->group(function () {
        Route::get('/', [NormalAdminController::class, 'index'])->name('admin.dashboard');

        // KEGIATAN
        Route::get('/kegiatan/list', [NormalAdminController::class, 'kegiatanList']);
        Route::get('/kegiatan/{id}', [NormalAdminController::class, 'kegiatanDetail']);
        Route::post('/kegiatan/store', [NormalAdminController::class, 'kegiatanStore']);
        Route::post('/kegiatan/{id}', [NormalAdminController::class, 'kegiatanUpdate']);
        Route::delete('/kegiatan/{id}', [NormalAdminController::class, 'kegiatanDelete']);
    });

Route::get('/phpinfo', function () {
    phpinfo();
});

