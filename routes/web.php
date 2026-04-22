<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - GEO-SINFRA
|--------------------------------------------------------------------------
*/

// ==========================================================
// 1. HALAMAN PUBLIK (Bisa diakses tanpa Login)
// ==========================================================

Route::get('/', function () {
    return view('dashboard'); // Landing Page Utama
});

// Autentikasi: Login & Logout
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Registrasi
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Fitur Lupa Password
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'updatePassword'])->name('password.update');


// ==========================================================
// 2. JALUR PRIVAT (Wajib Login & Cek Role)
// ==========================================================

Route::middleware(['auth'])->group(function () {
    
    // --- AREA ADMIN ---
    // Menggunakan AdminController agar bisa menampilkan Statistik di Dashboard
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        
        // Nanti kamu bisa tambah rute manajemen user di sini:
        // Route::get('/users', [AdminController::class, 'manageUsers'])->name('admin.users');
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