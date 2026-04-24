<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB; // Tambahkan ini agar bisa memanggil database

/*
|--------------------------------------------------------------------------
| Web Routes - GEO-SINFRA
|--------------------------------------------------------------------------
*/

// ==========================================================
// 1. HALAMAN PUBLIK (Tanpa Login)
// ==========================================================

Route::get('/', function () {
    // PERBAIKAN: Ambil data agar peta di Landing Page tidak error
    $semuaWilayah = DB::table('kecamatan')->whereNull('deleted_at')->get();
    $dataInfrastruktur = DB::table('infrastruktur')->whereNull('deleted_at')->get();

    // Kirim data ke view dashboard
    return view('dashboard', compact('semuaWilayah', 'dataInfrastruktur')); 
});

// Autentikasi
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Registrasi & Lupa Password
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'updatePassword'])->name('password.update');


// ==========================================================
// 2. JALUR PRIVAT (Wajib Login)
// ==========================================================

Route::middleware(['auth'])->group(function () {
    
    // --- AREA ADMIN ---
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        // Dashboard Statistik
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        
        // PETA SPASIAL
        Route::get('/peta', [AdminController::class, 'peta'])->name('admin.peta');
    });

    // --- AREA SURVEYOR ---
    Route::middleware(['role:surveyor'])->prefix('surveyor')->group(function () {
        Route::get('/dashboard', function () { 
            return view('surveyor.dashboard'); 
        })->name('surveyor.dashboard');
    });

    // --- AREA KABID ---
    Route::middleware(['role:kabid'])->prefix('kabid')->group(function () {
        Route::get('/dashboard', function () { 
            return view('kabid.dashboard'); 
        })->name('kabid.dashboard');
    });
    
});