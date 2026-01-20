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

// ===========================
// SUPERADMIN — FULL ACCESS
// ===========================
Route::prefix('superadmin')->group(function () {

    Route::get('/', [SuperAdminController::class, 'index'])
        ->name('superadmin.dashboard');

    // PROFIL
    Route::get('/profil', [SuperAdminController::class, 'profilIndex'])
        ->name('superadmin.profil');

    Route::post('/profil/store', [SuperAdminController::class, 'profilStore'])
        ->name('superadmin.profil.store');

    Route::post('/profil/update/{id}', [SuperAdminController::class, 'profilUpdate'])
        ->name('superadmin.profil.update');

    Route::delete('/profil/delete/{id}', [SuperAdminController::class, 'profilDelete'])
        ->name('superadmin.profil.delete');

    // VIDEO
    Route::post('/video/store', [SuperAdminController::class, 'videoStore'])
        ->name('superadmin.video.store');

    Route::post('/video/update/{id}', [SuperAdminController::class, 'videoUpdate'])
        ->name('superadmin.video.update');

    Route::delete('/video/delete/{id}', [SuperAdminController::class, 'videoDelete'])
        ->name('superadmin.video.delete');

    // KEGIATAN
    Route::post('/kegiatan/store', [SuperAdminController::class, 'kegiatanStore'])
        ->name('superadmin.kegiatan.store');

    Route::post('/kegiatan/update/{id}', [SuperAdminController::class, 'kegiatanUpdate'])
        ->name('superadmin.kegiatan.update');

    Route::delete('/kegiatan/delete/{id}', [SuperAdminController::class, 'kegiatanDelete'])
        ->name('superadmin.kegiatan.delete');

    // RUNNING TEXT
    Route::post('/superadmin/runningtext/store', [SuperAdminController::class, 'runningtextStore'])
        ->name('superadmin.runningtext.store');
    Route::post('/superadmin/runningtext/update/{id}', [SuperAdminController::class, 'runningtextUpdate'])
        ->name('superadmin.runningtext.update');
    Route::delete('/superadmin/runningtext/delete/{id}', [SuperAdminController::class, 'runningtextDelete'])
        ->name('superadmin.runningtext.delete');

    // NORMAL ADMIN MANAGEMENT
    Route::get('/normaladmin', [SuperAdminController::class, 'normalAdminIndex'])
        ->name('superadmin.normaladmin');

    Route::post('/normaladmin/store', [SuperAdminController::class, 'normalAdminStore'])
        ->name('superadmin.normaladmin.store');

    Route::post('/normaladmin/update/{id}', [SuperAdminController::class, 'normalAdminUpdate'])
        ->name('superadmin.normaladmin.update');

    Route::delete('/normaladmin/delete/{id}', [SuperAdminController::class, 'normalAdminDelete'])
        ->name('superadmin.normaladmin.delete');
});

// ===========================
// NORMAL ADMIN
// ===========================
Route::prefix('admin')->group(function () {

    // DASHBOARD NORMAL ADMIN
    Route::get('/', [NormalAdminController::class, 'index'])
        ->name('admin.dashboard');

    // SIMPAN AGENDA
    Route::post('/kegiatan/store', [NormalAdminController::class, 'kegiatanStore'])
        ->name('admin.kegiatan.store');

    // UPDATE AGENDA
    Route::post('/kegiatan/update/{id}', [NormalAdminController::class, 'kegiatanUpdate'])
        ->name('admin.kegiatan.update');

    // DELETE AGENDA
    Route::delete('/kegiatan/delete/{id}', [NormalAdminController::class, 'kegiatanDelete'])
        ->name('admin.kegiatan.delete');
});
