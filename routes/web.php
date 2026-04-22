<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// ==========================================================
// 1. HALAMAN PUBLIK (Bisa diakses siapa saja tanpa login)
// ==========================================================

// Landing Page / Beranda Publik
Route::get('/', function () {
    return view('dashboard'); 
});

// Jalur Login & Logout
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Jalur Registrasi Akun Baru
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Jalur Lupa Password (Minta Link)
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');

// Jalur Reset Password (Proses Ganti Sandi Baru via Token Email)
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'updatePassword'])->name('password.update');


// ==========================================================
// 2. JALUR RAHASIA (Hanya bisa diakses jika SUDAH LOGIN)
// ==========================================================

Route::middleware(['auth'])->group(function () {
    
    // Dashboard Admin
    Route::get('/admin/dashboard', function () { 
        return view('admin.dashboard'); 
    })->middleware('role:admin');

    // Dashboard Surveyor (Input Data Lapangan)
    Route::get('/surveyor/dashboard', function () { 
        return view('surveyor.dashboard'); 
    })->middleware('role:surveyor');

    // Dashboard Kabid (Validasi AI & Grafik)
    Route::get('/kabid/dashboard', function () { 
        return view('kabid.dashboard'); 
    })->middleware('role:kabid');
    
});