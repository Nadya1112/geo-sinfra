<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// 1. PINDAHKAN DASHBOARD CANTIKMU KE SINI (Halaman Publik)
// Agar bisa dibuka tanpa login
Route::get('/', function () {
    return view('dashboard'); // Pastikan file dashboard kamu namanya 'landing.blade.php'
});

// 2. Rute Login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 3. Rute Rahasia (Harus Login)
Route::middleware(['auth'])->group(function () {
    
    // Dashboard khusus setelah login (biasanya isinya tabel data/input)
    Route::get('/admin/dashboard', function () { return view('admin.dashboard'); })->middleware('role:admin');
    Route::get('/surveyor/dashboard', function () { return view('surveyor.dashboard'); })->middleware('role:surveyor');
    Route::get('/kabid/dashboard', function () { return view('kabid.dashboard'); })->middleware('role:kabid');

    // Halaman Registrasi
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Halaman Lupa Password
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    
});