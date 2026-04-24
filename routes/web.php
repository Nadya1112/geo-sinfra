<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes - GEO-SINFRA
|--------------------------------------------------------------------------
*/

// ==========================================================
// 1. HALAMAN PUBLIK (Akses Tanpa Login)
// ==========================================================

Route::get('/', function () {
    $semuaWilayah = DB::table('kecamatan')->whereNull('deleted_at')->get();
    $dataInfrastruktur = DB::table('infrastruktur')->whereNull('deleted_at')->get();

    return view('dashboard', compact('semuaWilayah', 'dataInfrastruktur')); 
});

/** * Grup Autentikasi 
 */
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'updatePassword'])->name('password.update');


// ==========================================================
// 2. JALUR PRIVAT (Wajib Login / Auth)
// ==========================================================

Route::middleware(['auth'])->group(function () {
    
    // --- AREA ADMIN SINFRA ---
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        
        /** * 1. DASHBOARD & STATISTIK (Sudah Dipisah)
         */
        // Halaman Welcome/Beranda Admin
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        
        // Halaman Khusus Angka & Laporan
        Route::get('/statistik', [AdminController::class, 'statistik'])->name('admin.statistik');

        /** * 2. MANAJEMEN PENGGUNA (Fitur Lengkap)
         */
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
        Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
        Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');

        /** * 3. MANAJEMEN INFRASTRUKTUR (Persiapan/Bakal Datang)
         */
        // Route::get('/infrastruktur', [AdminController::class, 'infrastruktur'])->name('admin.infrastruktur');
        
        /** * 4. PETA SPASIAL
         */
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