<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AnalisisAiController;
use App\Http\Controllers\AIPredictController;
use App\Http\Controllers\PublicReportController;
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

Route::view('/offline', 'offline')->name('offline');
Route::post('/lapor-warga', [PublicReportController::class, 'store'])->name('lapor.warga')->middleware('throttle:3,1');

Route::get('/debug-schema', function () {
    return response()->json([
        'users' => \Illuminate\Support\Facades\Schema::getColumnListing('users'),
        'infrastruktur' => \Illuminate\Support\Facades\Schema::getColumnListing('infrastruktur')
    ]);
});

Route::get('/', function () {
    // PROTEKSI: Jika tabel belum ada di database (misal belum migrate), jangan crash.
    if (!\Illuminate\Support\Facades\Schema::hasTable('kecamatan') || !\Illuminate\Support\Facades\Schema::hasTable('infrastruktur')) {
        return view('landing', [
            'semuaWilayah' => collect(),
            'dataInfrastruktur' => collect(),
            'dataKelurahan' => collect(),
            'stats' => ['total' => 0, 'kecamatan' => 0, 'rusak_berat' => 0, 'akurasi_ai' => 0],
            'sebaranKecamatan' => collect(),
            'topKategori' => '-',
            'topKategoriCount' => 0,
            'kondisiWilayah' => collect(),
        ]);
    }

    $semuaWilayah = DB::table('kecamatan')->whereNull('deleted_at')->get();
    
    // Ambil data infrastruktur dengan join ke kelurahan untuk memastikan id_kecamatan selalu ada
    $dataInfrastruktur = DB::table('infrastruktur')
        ->leftJoin('kelurahan', 'infrastruktur.id_kelurahan', '=', 'kelurahan.id_kelurahan')
        ->leftJoin('kecamatan', 'kelurahan.id_kecamatan', '=', 'kecamatan.id_kecamatan')
        ->leftJoin('analisis_ai', 'infrastruktur.id_infrastruktur', '=', 'analisis_ai.id_infrastruktur')
        ->leftJoin('users', 'infrastruktur.id_user', '=', 'users.id')
        ->select(
            'infrastruktur.*', 
            'kelurahan.id_kecamatan as id_kecamatan_from_kel',
            'kelurahan.nama_kelurahan',
            'kecamatan.nama_kecamatan',
            'users.name as nama_surveyor',
            'analisis_ai.label_prioritas',
            'analisis_ai.skor_dt'
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
    $totalAktif = $dataInfrastruktur->count();
    $totalDianalisis = $dataInfrastruktur->filter(fn($i) => !is_null($i->label_prioritas))->count();
    // Akurasi = cakupan analisis AI (% data yang sudah teranalisis)
    $akurasiAi = $totalAktif > 0 ? round(($totalDianalisis / $totalAktif) * 100) : 0;

    $stats = [
        'total'       => $totalAktif,
        'kecamatan'   => $semuaWilayah->count(),
        'rusak_berat' => $dataInfrastruktur->where('label_prioritas', 'Rusak Berat')->count(),
        'akurasi_ai'  => $akurasiAi,  // dinamis — % data teranalisis AI
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
            'baik' => $infraKec->where('label_prioritas', 'Baik')->count(),
            'rusak_sedang' => $infraKec->where('label_prioritas', 'Rusak Sedang')->count(),
            'rusak_berat' => $infraKec->where('label_prioritas', 'Rusak Berat')->count(),
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

// API Endpoint for Map Polling
Route::get('/api/map-data', function () {
    if (!\Illuminate\Support\Facades\Schema::hasTable('infrastruktur') || !\Illuminate\Support\Facades\Schema::hasTable('kecamatan')) {
        return response()->json([
            'infrastruktur' => [],
            'stats' => ['total' => 0, 'kecamatan' => 0, 'rusak_berat' => 0, 'akurasi_ai' => 0]
        ]);
    }

    $tahun = request('tahun');
    
    $query = DB::table('infrastruktur')
        ->leftJoin('kelurahan', 'infrastruktur.id_kelurahan', '=', 'kelurahan.id_kelurahan')
        ->leftJoin('kecamatan', 'kelurahan.id_kecamatan', '=', 'kecamatan.id_kecamatan')
        ->leftJoin('analisis_ai', 'infrastruktur.id_infrastruktur', '=', 'analisis_ai.id_infrastruktur')
        ->leftJoin('users', 'infrastruktur.id_user', '=', 'users.id')
        ->select(
            'infrastruktur.*', 
            'kelurahan.id_kecamatan as id_kecamatan_from_kel',
            'kelurahan.nama_kelurahan',
            'kecamatan.nama_kecamatan',
            'users.name as nama_surveyor',
            'analisis_ai.label_prioritas',
            'analisis_ai.skor_dt'
        )
        ->whereNull('infrastruktur.deleted_at');

    if ($tahun && $tahun !== 'all') {
        $query->whereYear('infrastruktur.created_at', $tahun);
    }

    $dataInfrastruktur = $query->get()
        ->map(function($item) {
            $item->id_kecamatan = $item->id_kecamatan ?? $item->id_kecamatan_from_kel;
            return $item;
        });

    $totalAktif = $dataInfrastruktur->count();
    $totalDianalisis = $dataInfrastruktur->filter(fn($i) => !is_null($i->label_prioritas))->count();
    $akurasiAi = $totalAktif > 0 ? round(($totalDianalisis / $totalAktif) * 100) : 0;

    $stats = [
        'total'       => $totalAktif,
        'kecamatan'   => DB::table('kecamatan')->whereNull('deleted_at')->count(),
        'rusak_berat' => $dataInfrastruktur->where('label_prioritas', 'Rusak Berat')->count(),
        'akurasi_ai'  => $akurasiAi,
    ];

    return response()->json([
        'infrastruktur' => $dataInfrastruktur,
        'stats' => $stats
    ]);
});

// API Endpoint for Admin Notifications (Check New Reports)
Route::get('/api/check-laporan', function () {
    if (!\Illuminate\Support\Facades\Schema::hasTable('laporan_warga')) {
        return response()->json([
            'count' => 0,
            'reports' => [],
            'timestamp' => time()
        ]);
    }

    $lastChecked = request('last_checked');
    
    $query = DB::table('laporan_warga')
        ->where('status', 'menunggu')
        ->whereNull('deleted_at');
        
    if ($lastChecked) {
        // Find reports created after the last checked timestamp
        $query->where('created_at', '>', date('Y-m-d H:i:s', $lastChecked));
    }
    
    $newReports = $query->get();
    
    return response()->json([
        'count' => $newReports->count(),
        'reports' => $newReports,
        'timestamp' => time() // return current server time for the next check
    ]);
});

/** * Grup Autentikasi 
 */
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
// Throttle: maks 5 percobaan login per menit per IP (anti brute force)
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
// Throttle: maks 10 percobaan register per menit per IP
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1');
Route::get('/register/otp', [AuthController::class, 'showOtp'])->name('register.otp');
Route::post('/register/otp', [AuthController::class, 'verifyRegistrationOtp'])->name('register.verifyOtp')->middleware('throttle:5,1');
Route::post('/register/otp/resend', [AuthController::class, 'resendRegistrationOtp'])->name('register.resendOtp')->middleware('throttle:3,1');

Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'updatePassword'])->name('password.update');


// API Endpoint untuk memanggil model Python AI
// Throttle: maks 30 request per menit per IP — mencegah eksploitasi server AI
// Hanya bisa diakses oleh user yang sudah login (admin)
Route::middleware(['auth', 'throttle:30,1'])->post('/api/predict-infrastructure', [AIPredictController::class, 'predict'])->name('api.predict');

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
        // Export Excel (CSV)
        Route::get('/infrastruktur/export', [AdminController::class, 'exportExcel'])->name('admin.infrastruktur.export');
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
        
        // Proses Verifikasi Aset
        Route::post('/infrastruktur/{id}/verifikasi', [AdminController::class, 'verifikasiInfrastruktur'])->name('admin.infrastruktur.verifikasi');
        
        // Sinkronisasi Masal AI
        Route::post('/infrastruktur/sinkronisasi-ai', [AdminController::class, 'sinkronisasiAi'])->name('admin.infrastruktur.sinkronisasi-ai');
        
        // Proses Analisis AI
        Route::post('/infrastruktur/{id}/analisis-ai', [AnalisisAiController::class, 'prosesAnalisis'])->name('admin.infrastruktur.analisis-ai');

        /** * 5. MANAJEMEN LAPORAN WARGA
         */
        Route::get('/laporan-warga', [AdminController::class, 'laporanWarga'])->name('admin.laporan-warga');
        Route::put('/laporan-warga/{id}/status', [AdminController::class, 'updateStatusLaporanWarga'])->name('admin.laporan-warga.status');
        Route::put('/laporan-warga/{id}/assign', [AdminController::class, 'assignSurveyor'])->name('admin.laporan-warga.assign');
        Route::delete('/laporan-warga/{id}', [AdminController::class, 'destroyLaporanWarga'])->name('admin.laporan-warga.destroy');
        
        // Konversi Laporan Warga ke Infrastruktur
        Route::get('/laporan-warga/{id}/convert', [AdminController::class, 'createFromLaporan'])->name('admin.laporan-warga.convert');
        Route::post('/laporan-warga/{id}/convert', [AdminController::class, 'storeFromLaporan'])->name('admin.laporan-warga.convert.store');

        // Ekspor Laporan
        Route::get('/laporan-warga/export/pdf', [AdminController::class, 'exportPdfLaporan'])->name('admin.laporan-warga.pdf');
        Route::get('/laporan-warga/export/csv', [AdminController::class, 'exportCsvLaporan'])->name('admin.laporan-warga.csv');
        Route::get('/infrastruktur/export/csv', [AdminController::class, 'exportCsvInfrastruktur'])->name('admin.infrastruktur.csv');

        // Manajemen Profil
        Route::get('/profile', [AdminController::class, 'profile'])->name('admin.profile');
        Route::put('/profile', [AdminController::class, 'updateProfile'])->name('admin.profile.update');

        // Security & Audit
        Route::get('/activity', [AdminController::class, 'activity'])->name('admin.activity');
        Route::get('/activity/export', [AdminController::class, 'exportActivityExcel'])->name('admin.activity.export');
        
        // Simulasi AI Playground
        Route::get('/simulasi-ai', [AdminController::class, 'simulasiAi'])->name('admin.simulasi-ai');
        
        // Backup
        Route::post('/backup', [AdminController::class, 'backupDatabase'])->name('admin.backup');

        // Pengaturan Sistem
        Route::get('/settings', [App\Http\Controllers\SettingController::class, 'index'])->name('admin.settings');
        Route::post('/settings', [App\Http\Controllers\SettingController::class, 'update'])->name('admin.settings.update');

        // Test Koneksi AI
        Route::get('/test-ai', function() {
            try {
                $url = env('CNN_API_URL', 'http://127.0.0.1:5000/predict');
                // Kita gunakan GET sederhana atau HEAD untuk cek apakah port terbuka
                $response = Http::timeout(3)->get(str_replace('/predict', '', $url));
                return response()->json([
                    'status' => 'CONNECTED',
                    'message' => 'Server AI Berhasil Terhubung!',
                    'url' => $url
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'DISCONNECTED',
                    'message' => 'Gagal terhubung ke Server AI. Pastikan server Python di port 5000 sudah menyala.',
                    'error' => $e->getMessage()
                ], 500);
            }
        })->name('admin.test-ai');

    });

    // --- AREA SURVEYOR ---
    Route::middleware(['role:surveyor'])->prefix('surveyor')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Surveyor\SurveyorController::class, 'index'])->name('surveyor.dashboard');
        
        // Penugasan Laporan Warga
        Route::get('/laporan', [App\Http\Controllers\Surveyor\SurveyorController::class, 'laporan'])->name('surveyor.laporan');
        Route::put('/laporan/{id}/status', [App\Http\Controllers\Surveyor\SurveyorController::class, 'updateStatus'])->name('surveyor.laporan.status');
        
        Route::get('/input', [App\Http\Controllers\Surveyor\SurveyorController::class, 'create'])->name('surveyor.input');
        Route::post('/input', [App\Http\Controllers\Surveyor\SurveyorController::class, 'store'])->name('surveyor.store');
        Route::get('/history', [App\Http\Controllers\Surveyor\SurveyorController::class, 'history'])->name('surveyor.history');
        Route::get('/infrastruktur/{id}', [App\Http\Controllers\Surveyor\SurveyorController::class, 'show'])->name('surveyor.infrastruktur.show');
        Route::get('/infrastruktur/{id}/edit', [App\Http\Controllers\Surveyor\SurveyorController::class, 'edit'])->name('surveyor.infrastruktur.edit');
        Route::put('/infrastruktur/{id}', [App\Http\Controllers\Surveyor\SurveyorController::class, 'update'])->name('surveyor.infrastruktur.update');
        // Hapus data sendiri — hanya jika masih status Pending
        Route::delete('/infrastruktur/{id}', [App\Http\Controllers\Surveyor\SurveyorController::class, 'destroy'])->name('surveyor.infrastruktur.destroy');
        Route::get('/map', [App\Http\Controllers\Surveyor\SurveyorController::class, 'map'])->name('surveyor.map');
        Route::get('/profile', [App\Http\Controllers\Surveyor\SurveyorController::class, 'profile'])->name('surveyor.profile');
        Route::post('/profile', [App\Http\Controllers\Surveyor\SurveyorController::class, 'updateProfile'])->name('surveyor.profile.update');
        Route::post('/territory', [App\Http\Controllers\Surveyor\SurveyorController::class, 'updateTerritories'])->name('surveyor.territory.update');
    });

    // --- AREA TIM TEKNIS ---
    Route::middleware(['role:tim_teknis'])->prefix('tim-teknis')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\TimTeknis\TimTeknisController::class, 'index'])->name('tim_teknis.dashboard');
        Route::get('/monitoring', [App\Http\Controllers\TimTeknis\TimTeknisController::class, 'monitoring'])->name('tim_teknis.monitoring');
        Route::get('/prioritas', [App\Http\Controllers\TimTeknis\TimTeknisController::class, 'prioritas'])->name('tim_teknis.prioritas');

        Route::get('/validasi', [App\Http\Controllers\TimTeknis\TimTeknisController::class, 'validasi'])->name('tim_teknis.validasi');
        Route::post('/validasi/bulk', [App\Http\Controllers\TimTeknis\TimTeknisController::class, 'bulkValidasi'])->name('tim_teknis.validasi.bulk');
        Route::post('/validasi/{id}', [App\Http\Controllers\TimTeknis\TimTeknisController::class, 'prosesValidasi'])->name('tim_teknis.validasi.proses');

        Route::get('/laporan', [App\Http\Controllers\TimTeknis\TimTeknisController::class, 'laporan'])->name('tim_teknis.laporan');
        Route::get('/infrastruktur/{id}', [App\Http\Controllers\TimTeknis\TimTeknisController::class, 'show'])->name('tim_teknis.infrastruktur.show');
        Route::post('/infrastruktur/{id}/status-perbaikan', [App\Http\Controllers\TimTeknis\TimTeknisController::class, 'updateStatusPerbaikan'])->name('tim_teknis.perbaikan.update');
        Route::get('/infrastruktur/{id}/pdf', [App\Http\Controllers\TimTeknis\TimTeknisController::class, 'exportPdf'])->name('tim_teknis.infrastruktur.pdf');
        Route::get('/profile', [App\Http\Controllers\TimTeknis\TimTeknisController::class, 'profile'])->name('tim_teknis.profile');
        Route::put('/profile', [App\Http\Controllers\TimTeknis\TimTeknisController::class, 'updateProfile'])->name('tim_teknis.profile.update');
    });
    
});

