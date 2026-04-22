<?php

use Illuminate\Support\Facades\Route;

// 1. Mengarahkan halaman utama ke Dashboard Publik (GEO-SINFRA)
Route::get('/', function () {
    return view('dashboard');
});

// 2. Mengarahkan ke halaman Login Admin
// Baris ini wajib ada agar tombol LOGIN di dashboard tidak error
Route::get('/login', function () {
    return view('auth.login');
})->name('login');