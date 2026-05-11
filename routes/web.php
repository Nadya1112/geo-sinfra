<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AnalisisAiController; // <-- TAMBAHAN: Import Controller AI
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
    
    // Ambil data infrastruktur dengan join ke kelurahan untuk memastikan id_kecamatan selalu ada
    $dataInfrastruktur = DB::table('infrastruktur')
        ->leftJoin('kelurahan', 'infrastruktur.id_kelurahan', '=', 'kelurahan.id_kelurahan')
        ->leftJoin('kecamatan', 'kelurahan.id_kecamatan', '=', 'kecamatan.id_kecamatan')
        ->select(
            'infrastruktur.*', 
            'kelurahan.id_kecamatan as id_kecamatan_from_kel',
            'kecamatan.nama_kecamatan'
        )
        ->whereNull('infrastruktur.deleted_at')
        ->get()
        ->map(function($item) {
            // Gunakan id_kecamatan dari kelurahan jika di tabel infrastruktur kosong
            $item->id_kecamatan = $item->id_kecamatan ?? $item->id_kecamatan_from_kel;
            return $item;
        });

    $dataKelurahan = DB::table('kelurahan')->whereNull('deleted_at')->get();
    
    // Hitung statistik untuk Landing Page
    $stats = [
        'total' => $dataInfrastruktur->count(),
        'kecamatan' => $semuaWilayah->count(),
        'rusak_berat' => $dataInfrastruktur->where('kondisi', 'Rusak Berat')->count(),
        'akurasi_ai' => 98.2,
    ];

    // Sebaran Perkecamatan (Pastikan semua 5 kecamatan muncul walau data 0)
    $sebaranKecamatan = $semuaWilayah->mapWithKeys(function($kec) use ($dataInfrastruktur) {
        return [$kec->nama_kecamatan => $dataInfrastruktur->where('id_kecamatan', $kec->id_kecamatan)->count()];
    })->sortDesc();

    // Kategori Terbanyak
    $topKategori = $dataInfrastruktur->count() > 0 
        ? $dataInfrastruktur->groupBy('jenis')->map->count()->sortDesc()->keys()->first() 
        : '-';
    $topKategoriCount = $dataInfrastruktur->count() > 0 
        ? $dataInfrastruktur->groupBy('jenis')->map->count()->max() 
        : 0;

    // Ringkasan Kondisi Wilayah (Data Tabel)
    $kondisiWilayah = $semuaWilayah->map(function($kec) use ($dataInfrastruktur) {
        $infraKec = $dataInfrastruktur->where('id_kecamatan', $kec->id_kecamatan);
        return [
            'nama' => $kec->nama_kecamatan,
            'baik' => $infraKec->where('kondisi', 'Baik')->count(),
            'rusak_ringan' => $infraKec->where('kondisi', 'Rusak Ringan')->count(),
            'rusak_berat' => $infraKec->where('kondisi', 'Rusak Berat')->count(),
            'total' => $infraKec->count()
        ];
    })->sortByDesc('total');

    return view('landing', compact(
        'semuaWilayah', 
        'dataInfrastruktur', 
        'dataKelurahan', 
        'stats', 
        'sebaranKecamatan', 
        'topKategori', 
        'topKategoriCount',
        'kondisiWilayah'
    )); 
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
        
        /** * 1. DASHBOARD & STATISTIK 
         */
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/statistik', [AdminController::class, 'statistik'])->name('admin.statistik');
        Route::get('/statistik/tahunan', [AdminController::class, 'statistikTahunan'])->name('admin.statistik.tahunan');

        /** * 2. MANAJEMEN PENGGUNA (Fitur Lengkap CRUD)
         */
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
        Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
        Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');

        /** * 3. MANAJEMEN WILAYAH (Data Master Kecamatan & Kelurahan)
         */
        Route::get('/wilayah', [AdminController::class, 'wilayah'])->name('admin.wilayah');
        Route::get('/wilayah/create', [AdminController::class, 'createWilayah'])->name('admin.wilayah.create');
        Route::post('/wilayah', [AdminController::class, 'storeWilayah'])->name('admin.wilayah.store');
        Route::get('/wilayah/{id}/edit', [AdminController::class, 'editWilayah'])->name('admin.wilayah.edit');
        Route::put('/wilayah/{id}', [AdminController::class, 'updateWilayah'])->name('admin.wilayah.update');
        Route::delete('/wilayah/{id}', [AdminController::class, 'destroyWilayah'])->name('admin.wilayah.destroy');

        /** * 4. MANAJEMEN INFRASTRUKTUR (Lengkap CRUD)
         */
        // Halaman Utama Tabel Infrastruktur
        Route::get('/infrastruktur', [AdminController::class, 'infrastruktur'])->name('admin.infrastruktur');
        // Form Tambah Aset
        Route::get('/infrastruktur/create', [AdminController::class, 'createInfrastruktur'])->name('admin.infrastruktur.create');
        // Proses Simpan Aset
        Route::post('/infrastruktur', [AdminController::class, 'storeInfrastruktur'])->name('admin.infrastruktur.store');
        // Form Edit Aset
        Route::get('/infrastruktur/{id}/edit', [AdminController::class, 'editInfrastruktur'])->name('admin.infrastruktur.edit');
        // Detail Aset
        Route::get('/infrastruktur/{id}', [AdminController::class, 'showInfrastruktur'])->name('admin.infrastruktur.show');
        // Export PDF
        Route::get('/infrastruktur/{id}/pdf', [AdminController::class, 'exportPdf'])->name('admin.infrastruktur.pdf');
        // Proses Update Aset
        Route::put('/infrastruktur/{id}', [AdminController::class, 'updateInfrastruktur'])->name('admin.infrastruktur.update');
        // Proses Hapus Aset
        Route::delete('/infrastruktur/{id}', [AdminController::class, 'destroyInfrastruktur'])->name('admin.infrastruktur.destroy');
        
        // --- TAMBAHAN: RUTE ANALISIS AI (DECISION TREE) UNTUK ADMIN ---
        Route::post('/infrastruktur/{id}/analisis-ai', [AnalisisAiController::class, 'prosesAnalisis'])->name('admin.infrastruktur.analisis-ai');

        // Manajemen Profil
        Route::get('/profile', [AdminController::class, 'profile'])->name('admin.profile');
        Route::put('/profile', [AdminController::class, 'updateProfile'])->name('admin.profile.update');

    });

    // --- AREA SURVEYOR ---
    Route::middleware(['role:surveyor'])->prefix('surveyor')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Surveyor\SurveyorController::class, 'index'])->name('surveyor.dashboard');
        Route::get('/input', [App\Http\Controllers\Surveyor\SurveyorController::class, 'create'])->name('surveyor.input');
        Route::post('/input', [App\Http\Controllers\Surveyor\SurveyorController::class, 'store'])->name('surveyor.store');
        Route::get('/history', [App\Http\Controllers\Surveyor\SurveyorController::class, 'history'])->name('surveyor.history');
        Route::get('/infrastruktur/{id}', [App\Http\Controllers\Surveyor\SurveyorController::class, 'show'])->name('surveyor.infrastruktur.show');
        Route::get('/infrastruktur/{id}/edit', [App\Http\Controllers\Surveyor\SurveyorController::class, 'edit'])->name('surveyor.infrastruktur.edit');
        Route::put('/infrastruktur/{id}', [App\Http\Controllers\Surveyor\SurveyorController::class, 'update'])->name('surveyor.infrastruktur.update');
        Route::get('/map', [App\Http\Controllers\Surveyor\SurveyorController::class, 'map'])->name('surveyor.map');
        Route::get('/profile', [App\Http\Controllers\Surveyor\SurveyorController::class, 'profile'])->name('surveyor.profile');
        Route::post('/profile', [App\Http\Controllers\Surveyor\SurveyorController::class, 'updateProfile'])->name('surveyor.profile.update');
        Route::post('/territory', [App\Http\Controllers\Surveyor\SurveyorController::class, 'updateTerritories'])->name('surveyor.territory.update');
    });

    // --- AREA KABID ---
    Route::middleware(['role:kabid'])->prefix('kabid')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Kabid\KabidController::class, 'index'])->name('kabid.dashboard');
        Route::get('/monitoring', [App\Http\Controllers\Kabid\KabidController::class, 'monitoring'])->name('kabid.monitoring');
        Route::get('/verifikasi', [App\Http\Controllers\Kabid\KabidController::class, 'verifikasi'])->name('kabid.verifikasi');
        Route::get('/statistik/tahunan', [App\Http\Controllers\Kabid\KabidController::class, 'statistikTahunan'])->name('kabid.statistik.tahunan');
        Route::get('/laporan', [App\Http\Controllers\Kabid\KabidController::class, 'laporan'])->name('kabid.laporan');
        Route::post('/verifikasi/{id}', [App\Http\Controllers\Kabid\KabidController::class, 'prosesVerifikasi'])->name('kabid.verifikasi.proses');
        Route::get('/infrastruktur/{id}', [App\Http\Controllers\Kabid\KabidController::class, 'show'])->name('kabid.infrastruktur.show');
        
        // --- TAMBAHAN: RUTE ANALISIS AI (DECISION TREE) UNTUK KABID ---
        Route::post('/infrastruktur/{id}/analisis-ai', [AnalisisAiController::class, 'prosesAnalisis'])->name('kabid.infrastruktur.analisis-ai');

        Route::get('/profile', [App\Http\Controllers\Kabid\KabidController::class, 'profile'])->name('kabid.profile');
        Route::post('/profile', [App\Http\Controllers\Kabid\KabidController::class, 'updateProfile'])->name('kabid.profile.update');
    });
    
});