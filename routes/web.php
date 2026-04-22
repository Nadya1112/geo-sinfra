<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// ==========================================================
// 1. HALAMAN PUBLIK (Bisa diakses siapa saja tanpa login)
// ==========================================================

Route::get('/', function () {
    return view('dashboard'); 
});

// Jalur Login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Jalur Registrasi (Sekarang sudah di luar, jadi bisa diklik!)
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Jalur Lupa Password
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');


// ==========================================================
// 2. JALUR RAHASIA (Hanya bisa diakses jika SUDAH LOGIN)
// ==========================================================

Route::middleware(['auth'])->group(function () {
    
    // Dashboard sesuai Role
    Route::get('/admin/dashboard', function () { 
        return view('admin.dashboard'); 
    })->middleware('role:admin');

    Route::get('/surveyor/dashboard', function () { 
        return view('surveyor.dashboard'); 
    })->middleware('role:surveyor');

    Route::get('/kabid/dashboard', function () { 
        return view('kabid.dashboard'); 
    })->middleware('role:kabid');
    
});