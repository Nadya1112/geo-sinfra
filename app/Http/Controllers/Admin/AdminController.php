<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Infrastruktur;
use App\Models\LaporanWarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Traits\AiProcessingTrait;
use App\Models\ActivityLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AdminController extends Controller
{
    use AiProcessingTrait;
    /**
     * Menampilkan Dashboard Utama
     */
    public function index()
    {
        // 1. Data Total Infrastruktur
        $totalInfrastruktur = Infrastruktur::whereNull('deleted_at')->count();

        // 2. Data Kondisi Aset (Berdasarkan Label Prioritas AI)
        $aiData = DB::table('analisis_ai')
            ->join('infrastruktur', 'analisis_ai.id_infrastruktur', '=', 'infrastruktur.id_infrastruktur')
            ->whereNull('infrastruktur.deleted_at')
            ->select('analisis_ai.label_prioritas')
            ->get();

        $rusakBerat = $aiData->where('label_prioritas', 'Rusak Berat')->count();
        $rusakSedang = $aiData->where('label_prioritas', 'Rusak Sedang')->count();
        $kondisiBaik = $aiData->where('label_prioritas', 'Baik')->count();
        $totalDianalisis = $aiData->count();
        
        $persenDianalisis = $totalInfrastruktur > 0 ? round(($totalDianalisis / $totalInfrastruktur) * 100) : 0;

        // 3. Rekomendasi AI (Ambil 1 aset dengan skor DT tertinggi / Rusak Berat)
        $rekomendasi = DB::table('infrastruktur')
            ->join('analisis_ai', 'infrastruktur.id_infrastruktur', '=', 'analisis_ai.id_infrastruktur')
            ->leftJoin('kelurahan', 'infrastruktur.id_kelurahan', '=', 'kelurahan.id_kelurahan')
            ->whereNull('infrastruktur.deleted_at')
            ->where('analisis_ai.label_prioritas', 'Rusak Berat')
            ->orderBy('analisis_ai.skor_dt', 'desc')
            ->select('infrastruktur.id_infrastruktur', 'infrastruktur.nama_objek', 'kelurahan.nama_kelurahan')
            ->first();

        // 4. Data Cepat Lainnya
        $totalUser = User::whereIn('role', ['surveyor', 'admin'])->count();
        $totalWilayah = DB::table('kelurahan')->count();
        $totalLaporanWarga = \App\Models\LaporanWarga::count();

        return view('admin.dashboard', compact(
            'totalInfrastruktur', 'rusakBerat', 'rusakSedang', 'kondisiBaik', 
            'persenDianalisis', 'rekomendasi', 'totalUser', 'totalWilayah', 'totalLaporanWarga'
        ));
    }

    /**
     * Helper untuk mencatat aktivitas
     */
    public function verifikasiInfrastruktur($id)
    {
        $infra = Infrastruktur::findOrFail($id);
        
        $infra->update([
            'status_verifikasi' => 'Verified',
            'updated_at' => now()
        ]);

        $this->logActivity('verification', "Verifikasi Aset: {$infra->nama_objek}", $id);

        // Kirim Notifikasi WA ke Tim Teknis bahwa ada data yang perlu di-ACC
        $infra->load(['kelurahan.kecamatan', 'user']);
        \App\Services\WhatsAppService::sendApprovalNotification($infra);

        return redirect()->back()->with('success', "Aset {$infra->nama_objek} berhasil diverifikasi. Notifikasi dikirim ke Tim Teknis.");
    }

    private function logActivity($type, $description, $referenceId = null)
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'type' => $type,
            'description' => $description,
            'reference_id' => $referenceId,
            'ip_address' => request()->ip()
        ]);
    }

    /**
     * Menampilkan Pusat Statistik dan Laporan (SINFRA)
     */
    public function statistik()
    {
        $jumlahSurveyor = User::where('role', 'surveyor')->count();
        $jumlahTimTeknis = User::where('role', 'tim_teknis')->count();
        $jumlahWilayah = DB::table('kecamatan')->count();
        $jumlahInfrastruktur = Infrastruktur::whereNull('deleted_at')->count();
        
        // Menghitung jumlah data yang sudah dianalisis AI
        $jumlahAnalisis = DB::table('analisis_ai')->count();

        // Data Kondisi BERDASARKAN HASIL AI (Agar lebih akurat untuk laporan TA)
        $hasilAi = DB::table('analisis_ai')->get();
        $jumlahRusakBerat = $hasilAi->where('label_prioritas', 'Rusak Berat')->count();
        $jumlahRusakSedang = $hasilAi->where('label_prioritas', 'Rusak Sedang')->count(); 
        $jumlahBaik = $hasilAi->where('label_prioritas', 'Baik')->count();
        $jumlahBelumDianalisis = $jumlahInfrastruktur - ($jumlahRusakBerat + $jumlahRusakSedang + $jumlahBaik);

        $recentActivities = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.statistik', compact(
            'jumlahSurveyor', 'jumlahTimTeknis', 'jumlahWilayah', 'jumlahInfrastruktur', 
            'jumlahAnalisis', 'jumlahRusakBerat', 'jumlahRusakSedang', 'jumlahBaik', 'jumlahBelumDianalisis',
            'recentActivities'
        ));
    }

    /**
     * Menampilkan Statistik Tahunan Berbasis Batang (Bar Chart) & Rekapitulasi Wilayah AI
     */
    public function statistikTahunan(Request $request)
    {
        $year = $request->query('year', date('Y'));
        
        $driver = DB::connection()->getDriverName();
        $sqlYear = $driver === 'sqlite' ? "strftime('%Y', COALESCE(tgl_survey, created_at))" : "YEAR(COALESCE(tgl_survey, created_at))";
        $sqlMonth = $driver === 'sqlite' ? "CAST(strftime('%m', COALESCE(tgl_survey, created_at)) AS INTEGER)" : "MONTH(COALESCE(tgl_survey, created_at))";
        $sqlYearInfrastruktur = $driver === 'sqlite' ? "strftime('%Y', COALESCE(infrastruktur.tgl_survey, infrastruktur.created_at))" : "YEAR(COALESCE(infrastruktur.tgl_survey, infrastruktur.created_at))";
        
        $minYear = DB::table('infrastruktur')
            ->select(DB::raw("MIN($sqlYear) as min_year"))
            ->value('min_year');
            
        $currentYear = (int) date('Y');
        $startYear = $minYear ? min((int) $minYear, $currentYear) : $currentYear;
        
        $availableYears = range($currentYear, $startYear);
        
        if (!in_array((int)$year, $availableYears)) {
            $availableYears[] = (int) $year;
            rsort($availableYears);
        }
        
        // Data Perbulan (Jan - Des)
        $monthlyData = DB::table('infrastruktur')
            ->select(DB::raw("$sqlMonth as month"), DB::raw('count(*) as total'))
            ->where(DB::raw($sqlYear), $year)
            ->whereNull('deleted_at')
            ->groupBy('month')
            ->get()
            ->pluck('total', 'month')
            ->all();

        // Fill missing months with 0
        $chartData = [];
        for ($m = 1; $m <= 12; $m++) {
            $chartData[] = $monthlyData[$m] ?? 0;
        }

        // Statistik per Jenis (Tahunan)
        $statsJenis = DB::table('infrastruktur')
            ->select('jenis', DB::raw('count(*) as total'))
            ->where(DB::raw($sqlYear), $year)
            ->whereNull('deleted_at')
            ->groupBy('jenis')
            ->get();

        // Sebaran Kondisi per Kecamatan (Tahunan) - 🌟 PERBAIKAN: Menghubungkan langsung ke Hasil Otak AI
        $semuaKecamatan = DB::table('kecamatan')->get();
        $kondisiKecamatan = [];
        foreach($semuaKecamatan as $kec) {
            $infraKec = DB::table('infrastruktur')
                ->leftJoin('kelurahan', 'infrastruktur.id_kelurahan', '=', 'kelurahan.id_kelurahan')
                ->leftJoin('analisis_ai', 'infrastruktur.id_infrastruktur', '=', 'analisis_ai.id_infrastruktur')
                ->where('kelurahan.id_kecamatan', $kec->id_kecamatan)
                ->where(DB::raw($sqlYearInfrastruktur), $year)
                ->whereNull('infrastruktur.deleted_at')
                ->select(
                    DB::raw("COUNT(CASE WHEN LOWER(analisis_ai.label_prioritas) LIKE '%baik%' THEN 1 END) as baik"),
                    DB::raw("COUNT(CASE WHEN LOWER(analisis_ai.label_prioritas) LIKE '%sedang%' THEN 1 END) as sedang"),
                    DB::raw("COUNT(CASE WHEN LOWER(analisis_ai.label_prioritas) LIKE '%berat%' THEN 1 END) as berat"),
                    DB::raw("COUNT(*) as total_semua")
                )
                ->first();
            
            $kondisiKecamatan[] = [
                'name' => $kec->nama_kecamatan,
                'nama' => $kec->nama_kecamatan,
                'baik' => $infraKec->baik ?? 0,
                'sedang' => $infraKec->sedang ?? 0,
                'berat' => $infraKec->berat ?? 0,
                'total' => $infraKec->total_semua ?? 0
            ];
        }

        return view('admin.statistik-tahunan', compact('chartData', 'statsJenis', 'kondisiKecamatan', 'year', 'availableYears'));
    }

    // ==========================================================
    // MODUL MANAJEMEN PENGGUNA
    // ==========================================================

    public function users(Request $request)
    {
        $search = $request->query('search');
        $query = User::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        if ($request->get('show') == 'all') {
            $users = $query->get();
        } else {
            $users = $query->paginate(10)->withQueryString();
        }

        return view('admin.users', compact('users'));
    }

    public function createUser()
    {
        $semuaWilayah = DB::table('kecamatan')->get();
        return view('admin.create-user', compact('semuaWilayah'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'no_hp' => 'nullable|string|max:20',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,surveyor',
            'id_kecamatan' => 'nullable|exists:kecamatan,id_kecamatan',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'no_hp' => $request->no_hp ?? null,
            'id_kecamatan' => ($request->role === 'admin') ? null : $request->id_kecamatan,
        ]);

        if ($user->role === 'surveyor' && $request->id_kecamatan) {
            $user->kecamatans()->sync([$request->id_kecamatan]);
        }

        $this->logActivity('user', "Menambahkan user baru: {$user->name} ({$user->role})", $user->id);

        return redirect()->route('admin.users')->with('success', 'USER BARU BERHASIL DITAMBAHKAN!');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->role === 'tim_teknis') return redirect()->route('admin.users')->with('error', 'AKUN TIM TEKNIS TERKUNCI.');
        
        $semuaWilayah = DB::table('kecamatan')->get();
        return view('admin.edit-user', compact('user', 'semuaWilayah'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'no_hp' => 'nullable|string|max:20',
            'role' => 'required|in:admin,surveyor',
            'id_kecamatan' => 'nullable|exists:kecamatan,id_kecamatan',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'no_hp' => $request->no_hp ?? $user->no_hp,
            'id_kecamatan' => ($request->role === 'admin') ? null : $request->id_kecamatan,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        if ($user->role === 'surveyor' && $request->id_kecamatan) {
            $user->kecamatans()->sync([$request->id_kecamatan]);
        } elseif ($user->role === 'admin') {
            $user->kecamatans()->detach();
        }

        $this->logActivity('user', "Memperbarui data user: {$user->name}", $user->id);

        return redirect()->route('admin.users')->with('success', 'DATA USER BERHASIL DIPERBARUI!');
    }

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->role === 'tim_teknis') return redirect()->route('admin.users')->with('error', 'AKUN TIM TEKNIS DILINDUNGI.');
        if (auth()->id() == $user->id) return redirect()->route('admin.users')->with('error', 'TIDAK BISA MENGHAPUS AKUN SENDIRI.');

        $name = $user->name;
        $user->forceDelete();

        $this->logActivity('user', "Menghapus user: {$name}");

        return redirect()->route('admin.users')->with('success', 'USER BERHASIL DIHAPUS!');
    }


    // ==========================================================
    // MODUL MANAJEMEN WILAYAH
    // ==========================================================

    public function wilayah(Request $request)
    {
        $search = $request->query('search');
        $query = DB::table('kelurahan')
            ->join('kecamatan', 'kelurahan.id_kecamatan', '=', 'kecamatan.id_kecamatan')
            ->leftJoin('infrastruktur', 'kelurahan.id_kelurahan', '=', 'infrastruktur.id_kelurahan')
            ->select('kelurahan.*', 'kecamatan.nama_kecamatan', DB::raw('COUNT(infrastruktur.id_infrastruktur) as total_aset'))
            ->groupBy('kelurahan.id_kelurahan', 'kecamatan.nama_kecamatan', 'kelurahan.id_kecamatan', 'kelurahan.nama_kelurahan', 'kelurahan.geometri', 'kelurahan.created_at', 'kelurahan.updated_at');

        if ($search) {
            $query->where('kelurahan.nama_kelurahan', 'LIKE', "%{$search}%")
                  ->orWhere('kecamatan.nama_kecamatan', 'LIKE', "%{$search}%");
        }

        $query = $query->orderBy('kelurahan.id_kelurahan', 'asc');
        
        if ($request->get('show') == 'all') {
            $wilayah = $query->get();
        } else {
            $wilayah = $query->paginate(10)->withQueryString();
        }

        return view('admin.wilayah', compact('wilayah'));
    }

    public function createWilayah()
    {
        $semuaKecamatan = DB::table('kecamatan')->get();
        return view('admin.create-wilayah', compact('semuaKecamatan'));
    }

    public function storeWilayah(Request $request)
    {
        $request->validate([
            'id_kecamatan' => 'required|exists:kecamatan,id_kecamatan',
            'nama_kelurahan' => 'required|string|max:100',
            'geometri' => 'nullable|string',
        ]);

        DB::table('kelurahan')->insert([
            'id_kecamatan' => $request->id_kecamatan,
            'nama_kelurahan' => $request->nama_kelurahan,
            'geometri' => $request->geometri,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->logActivity('wilayah', "Menambahkan wilayah baru: Kelurahan {$request->nama_kelurahan}");

        return redirect()->route('admin.wilayah')->with('success', 'DATA WILAYAH BERHASIL DITAMBAHKAN!');
    }

    public function editWilayah($id)
    {
        $wilayah = DB::table('kelurahan')->where('id_kelurahan', $id)->first();
        if (!$wilayah) return redirect()->route('admin.wilayah')->with('error', 'WILAYAH TIDAK DITEMUKAN.');
        
        $semuaKecamatan = DB::table('kecamatan')->get();
        return view('admin.edit-wilayah', compact('wilayah', 'semuaKecamatan'));
    }

    public function updateWilayah(Request $request, $id)
    {
        $request->validate([
            'id_kecamatan' => 'required|exists:kecamatan,id_kecamatan',
            'nama_kelurahan' => 'required|string|max:100',
            'geometri' => 'nullable|string',
        ]);

        DB::table('kelurahan')->where('id_kelurahan', $id)->update([
            'id_kecamatan' => $request->id_kecamatan,
            'nama_kelurahan' => $request->nama_kelurahan,
            'geometri' => $request->geometri,
            'updated_at' => now(),
        ]);

        $this->logActivity('wilayah', "Memperbarui data wilayah: Kelurahan {$request->nama_kelurahan}", $id);

        return redirect()->route('admin.wilayah')->with('success', 'DATA WILAYAH BERHASIL DIPERBARUI!');
    }

    public function destroyWilayah($id)
    {
        $wilayah = DB::table('kelurahan')->where('id_kelurahan', $id)->first();
        DB::table('kelurahan')->where('id_kelurahan', $id)->delete();

        $this->logActivity('wilayah', "Menghapus wilayah: Kelurahan {$wilayah->nama_kelurahan}");

        return redirect()->route('admin.wilayah')->with('success', 'DATA WILAYAH BERHASIL DIHAPUS.');
    }


    // ==========================================================
    // MODUL MANAJEMEN INFRASTRUKTUR (SINFRA)
    // ==========================================================

    public function infrastruktur(Request $request)
    {
        $search = $request->query('search');
        
        $query = DB::table('infrastruktur')
            ->leftJoin('kelurahan', 'infrastruktur.id_kelurahan', '=', 'kelurahan.id_kelurahan')
            ->leftJoin('kecamatan', 'kelurahan.id_kecamatan', '=', 'kecamatan.id_kecamatan')
            ->leftJoin('analisis_ai', 'infrastruktur.id_infrastruktur', '=', 'analisis_ai.id_infrastruktur')
            ->leftJoin('citra_cnn', 'infrastruktur.id_infrastruktur', '=', 'citra_cnn.id_infrastruktur')
            ->whereNull('infrastruktur.deleted_at');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('infrastruktur.nama_objek', 'LIKE', "%{$search}%")
                  ->orWhere('infrastruktur.jenis', 'LIKE', "%{$search}%")
                  ->orWhere('infrastruktur.id_infrastruktur', 'LIKE', "%{$search}%")
                  ->orWhere('kelurahan.nama_kelurahan', 'LIKE', "%{$search}%")
                  ->orWhere('kecamatan.nama_kecamatan', 'LIKE', "%{$search}%");
            });
        }

        $query = $query->orderBy('infrastruktur.id_infrastruktur', 'asc')
                               ->select(
                                   'infrastruktur.*', 
                                   'kelurahan.nama_kelurahan', 
                                   'kecamatan.nama_kecamatan', 
                                   'kelurahan.id_kecamatan',
                                   'analisis_ai.label_prioritas as dt_label_prioritas',
                                   'analisis_ai.skor_dt as dt_skor_dt',
                                   'analisis_ai.rekomendasi as dt_rekomendasi',
                                   'citra_cnn.label_kondisi as cnn_label_kondisi',
                                   'citra_cnn.skor_cnn as cnn_skor_cnn'
                               );

        if ($request->get('show') == 'all') {
            $infrastruktur = $query->get();
        } else {
            $infrastruktur = $query->paginate(10)->withQueryString();
        }

        $semuaKecamatan = DB::table('kecamatan')->get();
        $semuaKelurahan = DB::table('kelurahan')->get(); 
        
        return view('admin.infrastruktur', compact('infrastruktur', 'semuaKecamatan', 'semuaKelurahan'));
    }

    public function createInfrastruktur()
    {
        $semuaKecamatan = DB::table('kecamatan')->get();
        $semuaKelurahan = DB::table('kelurahan')->get();
        
        return view('admin.create-infrastruktur', compact('semuaKecamatan', 'semuaKelurahan'));
    }

    public function storeInfrastruktur(Request $request)
    {
        $request->validate([
            'nama_infrastruktur' => 'required|string|max:255',
            'jenis' => 'required|string|in:jalan,titian,jembatan',
            'id_kecamatan' => 'required|exists:kecamatan,id_kecamatan',
            'id_kelurahan' => 'required|exists:kelurahan,id_kelurahan',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'foto' => 'required|max:5120',
            'material_eksisting' => 'required|string',
            'panjang' => 'required|numeric',
            'lebar' => 'required|numeric',
            'kondisi' => 'required|string',
        ]);

        $namaFoto = 'default.jpg';
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $namaFoto = time() . '_' . $file->getClientOriginalName();
            
            // Resize gambar ke 300x300
            $manager = new ImageManager(new Driver());
            $image = $manager->decodePath($file->getRealPath());
            $image->resize(300, 300);
            
            // Pastikan folder ada
            if (!Storage::disk('public')->exists('infrastruktur')) {
                Storage::disk('public')->makeDirectory('infrastruktur');
            }
            
            $image->save(storage_path('app/public/infrastruktur/' . $namaFoto));
        }

        $infra = Infrastruktur::create([
            'id_user' => auth()->id(), 
            'id_kelurahan' => $request->id_kelurahan,
            'jenis' => strtolower($request->jenis),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'kondisi' => $request->kondisi, 
            'material_eksisting' => $request->material_eksisting,
            'panjang' => $request->panjang,
            'lebar' => $request->lebar,
            'has_drainase' => $request->has_drainase ?? 'tidak',
            'has_gorong_gorong' => $request->has_gorong_gorong ?? 'tidak',
            'foto_terbaru' => 'infrastruktur/' . $namaFoto,
            'nama_objek' => $request->nama_infrastruktur, 
        ]);

        if ($request->hasFile('foto')) {
            $this->processCnnAnalysis($infra->id_infrastruktur, 'infrastruktur/' . $namaFoto);
        }

        $this->logActivity('survey', "Input Aset Baru: {$infra->nama_infrastruktur}", $infra->id_infrastruktur);

        return redirect()->route('admin.infrastruktur')->with('success', 'ASET BERHASIL DISIMPAN & DIANALISIS AI!');
    }

    public function editInfrastruktur($id)
    {
        $inf = DB::table('infrastruktur')
            ->leftJoin('kelurahan', 'infrastruktur.id_kelurahan', '=', 'kelurahan.id_kelurahan')
            ->select('infrastruktur.*', 'kelurahan.id_kecamatan')
            ->where('id_infrastruktur', $id)
            ->first();
            
        if (!$inf) return redirect()->route('admin.infrastruktur')->with('error', 'ASET TIDAK DITEMUKAN.');
        
        $semuaKecamatan = DB::table('kecamatan')->get();
        $semuaKelurahan = DB::table('kelurahan')->get();
        
        return view('admin.edit-infrastruktur', compact('inf', 'semuaKecamatan', 'semuaKelurahan'));
    }

    public function showInfrastruktur($id)
    {
        $inf = DB::table('infrastruktur')
            ->leftJoin('kelurahan', 'infrastruktur.id_kelurahan', '=', 'kelurahan.id_kelurahan')
            ->leftJoin('kecamatan', 'kelurahan.id_kecamatan', '=', 'kecamatan.id_kecamatan')
            ->leftJoin('users', 'infrastruktur.id_user', '=', 'users.id')
            ->where('infrastruktur.id_infrastruktur', $id)
            ->select('infrastruktur.*', 'kecamatan.nama_kecamatan', 'kelurahan.nama_kelurahan', 'users.name as nama_user')
            ->first();
            
        if (!$inf) return redirect()->route('admin.infrastruktur')->with('error', 'ASET TIDAK DITEMUKAN.');
        
        return view('admin.detail-infrastruktur', compact('inf'));
    }

    public function exportPdf($id)
    {
        $inf = DB::table('infrastruktur')
            ->leftJoin('kelurahan', 'infrastruktur.id_kelurahan', '=', 'kelurahan.id_kelurahan')
            ->leftJoin('kecamatan', 'kelurahan.id_kecamatan', '=', 'kecamatan.id_kecamatan')
            ->leftJoin('users', 'infrastruktur.id_user', '=', 'users.id')
            ->leftJoin('citra_cnn', 'infrastruktur.id_infrastruktur', '=', 'citra_cnn.id_infrastruktur')
            ->leftJoin('analisis_ai', 'infrastruktur.id_infrastruktur', '=', 'analisis_ai.id_infrastruktur')
            ->where('infrastruktur.id_infrastruktur', $id)
            ->select('infrastruktur.*', 'kecamatan.nama_kecamatan', 'kelurahan.nama_kelurahan', 'users.name as nama_user', 'citra_cnn.skor_cnn', 'citra_cnn.label_kondisi as label_cnn', 'analisis_ai.skor_dt', 'analisis_ai.label_prioritas', 'analisis_ai.rekomendasi')
            ->first();
            
        if (!$inf) return redirect()->route('admin.infrastruktur')->with('error', 'ASET TIDAK DITEMUKAN.');
        
        $pdf = Pdf::loadView('admin.pdf-infrastruktur', compact('inf'))
            ->setOptions([
                'isPhpEnabled'    => true,   // izinkan @php Blade
                'dpi'             => 150,    // kualitas gambar lebih baik
                'defaultFont'     => 'Helvetica',
                'defaultPaperSize' => 'a4',
            ]);
        $pdf->setPaper('A4', 'portrait');

        $fileName = 'Laporan_Infrastruktur_' . str_replace(' ', '_', ($inf->nama_objek ?? 'Export')) . '.pdf';
        return $pdf->download($fileName);
    }

    public function exportExcel()
    {
        $infrastrukturs = DB::table('infrastruktur')
            ->leftJoin('kelurahan', 'infrastruktur.id_kelurahan', '=', 'kelurahan.id_kelurahan')
            ->leftJoin('kecamatan', 'kelurahan.id_kecamatan', '=', 'kecamatan.id_kecamatan')
            ->leftJoin('analisis_ai', 'infrastruktur.id_infrastruktur', '=', 'analisis_ai.id_infrastruktur')
            ->select(
                'infrastruktur.id_infrastruktur',
                'infrastruktur.nama_objek',
                'infrastruktur.jenis',
                'infrastruktur.material_eksisting',
                'kecamatan.nama_kecamatan',
                'kelurahan.nama_kelurahan',
                'infrastruktur.panjang',
                'infrastruktur.lebar',
                'infrastruktur.kondisi',
                'analisis_ai.label_prioritas',
                'analisis_ai.skor_dt',
                'infrastruktur.tgl_survey'
            )
            ->whereNull('infrastruktur.deleted_at')
            ->orderBy('infrastruktur.id_infrastruktur', 'asc')
            ->get();

        $headers = [
            "Content-type"        => "application/vnd.ms-excel; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=Rekap_Data_Infrastruktur_" . date('Y-m-d') . ".xls",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($infrastrukturs) {
            // HTML styling agar saat dibuka di Excel, border dan fontnya sesuai standar Excel
            $html = '<html><head><meta charset="UTF-8"></head><body>';
            $html .= '<table style="border-collapse: collapse; font-family: Calibri, sans-serif; font-size: 11pt;">';
            $html .= '<thead><tr>';
            $columns = [
                'NO', 'NAMA OBJEK', 'JENIS', 'MATERIAL', 'KECAMATAN', 'KELURAHAN', 
                'PANJANG (M)', 'LEBAR (M)', 'KONDISI', 'PRIORITAS AI', 'SKOR DT', 'TGL SURVEY'
            ];
            
            // Header dengan border tebal dan warna background
            foreach ($columns as $col) {
                $html .= '<th style="background-color: #1e1b4b; color: #ffffff; font-weight: bold; text-align: center; border: 1pt solid #000000; padding: 5px;">' . $col . '</th>';
            }
            $html .= '</tr></thead><tbody>';

            // Data rows dengan border tipis (0.5pt) seperti default border Excel
            $no = 1;
            foreach ($infrastrukturs as $inf) {
                $html .= '<tr>';
                $tdStyle = 'border: 0.5pt solid #000000; padding: 5px;';
                $html .= '<td style="' . $tdStyle . ' text-align: center;">' . $no++ . '</td>';
                $html .= '<td style="' . $tdStyle . '">' . $inf->nama_objek . '</td>';
                $html .= '<td style="' . $tdStyle . '">' . ucfirst($inf->jenis) . '</td>';
                $html .= '<td style="' . $tdStyle . '">' . $inf->material_eksisting . '</td>';
                $html .= '<td style="' . $tdStyle . '">' . $inf->nama_kecamatan . '</td>';
                $html .= '<td style="' . $tdStyle . '">' . $inf->nama_kelurahan . '</td>';
                $html .= '<td style="' . $tdStyle . ' text-align: right;">' . $inf->panjang . '</td>';
                $html .= '<td style="' . $tdStyle . ' text-align: right;">' . $inf->lebar . '</td>';
                $html .= '<td style="' . $tdStyle . '">' . $inf->kondisi . '</td>';
                $html .= '<td style="' . $tdStyle . '">' . ($inf->label_prioritas ?? 'Belum Dianalisis') . '</td>';
                $html .= '<td style="' . $tdStyle . ' text-align: right;">' . ($inf->skor_dt ?? '-') . '</td>';
                $html .= '<td style="' . $tdStyle . '">' . $inf->tgl_survey . '</td>';
                $html .= '</tr>';
            }
            $html .= '</tbody></table></body></html>';

            echo $html;
        };

        return \Illuminate\Support\Facades\Response::stream($callback, 200, $headers);
    }

    public function updateInfrastruktur(Request $request, $id)
    {
        $infra = Infrastruktur::findOrFail($id);
        
        $request->validate([
            'nama_infrastruktur' => 'required|string|max:255',
            'jenis' => 'required|string|in:jalan,titian,jembatan',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'material_eksisting' => 'required|string',
            'panjang' => 'required|numeric',
            'lebar' => 'required|numeric',
            'kondisi' => 'required|string',
        ]);

        $namaFoto = $infra->foto_terbaru;
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($namaFoto && $namaFoto != 'default.jpg') {
                $oldPath = str_replace('\\', '/', $namaFoto);
                Storage::disk('public')->delete(Str::replaceFirst('infrastruktur/', '', $oldPath)); 
                Storage::disk('public')->delete($oldPath);
                Storage::disk('public')->delete($namaFoto); // Delete original just in case
            }
            
            $file = $request->file('foto');
            $newFilename = time() . '_' . $file->getClientOriginalName();
            
            // Resize gambar ke 300x300
            $manager = new ImageManager(new Driver());
            $image = $manager->decodePath($file->getRealPath());
            $image->resize(300, 300);
            
            // Pastikan folder ada
            if (!Storage::disk('public')->exists('infrastruktur')) {
                Storage::disk('public')->makeDirectory('infrastruktur');
            }
            
            $image->save(storage_path('app/public/infrastruktur/' . $newFilename));
            $namaFoto = 'infrastruktur/' . $newFilename;
        }

        // UPDATE MENGGUNAKAN MODEL (Memicu AI Update otomatis)
        $infra->update([
            'id_kelurahan'       => $request->id_kelurahan,
            'latitude'           => $request->latitude,
            'longitude'          => $request->longitude,
            'kondisi'            => $request->kondisi,
            'material_eksisting' => $request->material_eksisting,
            'panjang'            => $request->panjang,
            'lebar'              => $request->lebar,
            'has_drainase'       => $request->has_drainase,
            'has_gorong_gorong'  => $request->has_gorong_gorong,
            'foto_terbaru'       => $namaFoto,
            'nama_objek'         => $request->nama_infrastruktur,
            'status_verifikasi'  => $request->status_verifikasi ?? $infra->status_verifikasi,
        ]);

        if ($request->hasFile('foto')) {
            $this->processCnnAnalysis($infra->id_infrastruktur, $namaFoto);
        }

        $this->logActivity('survey', "Update Aset: {$infra->nama_infrastruktur}", $id);

        return redirect()->route('admin.infrastruktur')->with('success', 'DATA DIPERBARUI & SKOR AI DIKALKULASI ULANG!');
    }



    /**
     * PROSES HAPUS DATA INFRASTRUKTUR (SINKRON DENGAN SOFTDELETES ELOQUENT)
     */
    public function destroyInfrastruktur($id)
    {
        // 🌟 PERBAIKAN: Menggunakan Eloquent agar memicu trigger SoftDeletes Model dengan aman
        $infra = Infrastruktur::findOrFail($id);
        $nama = $infra->nama_objek ?? $infra->nama_infrastruktur;
        
        $infra->forceDelete(); // Menghapus secara permanen dari database

        $this->logActivity('survey', "Menghapus data infrastruktur: {$nama}");

        return redirect()->route('admin.infrastruktur')->with('success', 'DATA INFRASTRUKTUR BERHASIL DIHAPUS.');
    }

    public function sinkronisasiAi()
    {
        $allInfra = \App\Models\Infrastruktur::all();
        $count = 0;

        foreach ($allInfra as $infra) {
            // 1. Trigger CNN jika belum ada (dan ada foto)
            $cnn = DB::table('citra_cnn')->where('id_infrastruktur', $infra->id_infrastruktur)->first();
            if (!$cnn && $infra->foto_terbaru && $infra->foto_terbaru != 'default.jpg') {
                $this->processCnnAnalysis($infra->id_infrastruktur, $infra->foto_terbaru);
            }

            // 2. Kalkulasi Hybrid
            \App\Models\AnalisisAi::calculateHybrid($infra->id_infrastruktur);
            $count++;
        }

        $this->logActivity('ai', "Sinkronisasi Masal AI: Memproses {$count} data infrastruktur.");

        return redirect()->route('admin.dashboard')->with('success', "BERHASIL! {$count} data infrastruktur telah dianalisis ulang oleh sistem cerdas.");
    }

    // ==========================================================
    // MODUL MANAJEMEN LAPORAN WARGA
    // ==========================================================

    public function laporanWarga(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $query = LaporanWarga::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_pelapor', 'LIKE', "%{$search}%")
                  ->orWhere('deskripsi', 'LIKE', "%{$search}%")
                  ->orWhere('no_hp', 'LIKE', "%{$search}%");
            });
        }

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $laporanWarga = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        
        $surveyors = User::where('role', 'Surveyor')->get();

        return view('admin.laporan-warga', compact('laporanWarga', 'status', 'search', 'surveyors'));
    }

    public function updateStatusLaporanWarga(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Menunggu,Ditinjau,Selesai,Ditolak',
        ]);

        $laporan = LaporanWarga::findOrFail($id);
        $laporan->update([
            'status' => $request->status,
        ]);

        $this->logActivity('laporan', "Update status laporan warga dari {$laporan->nama_pelapor} menjadi {$request->status}", $id);

        return redirect()->route('admin.laporan-warga')->with('success', 'STATUS LAPORAN BERHASIL DIPERBARUI!');
    }

    public function assignSurveyor(Request $request, $id)
    {
        $request->validate([
            'id_surveyor' => 'required|exists:users,id',
        ]);

        $laporan = LaporanWarga::findOrFail($id);
        $surveyor = User::findOrFail($request->id_surveyor);

        $laporan->update([
            'id_surveyor' => $request->id_surveyor,
            'status' => 'Diproses', // Automatically set to processing
        ]);

        $this->logActivity('laporan', "Menugaskan laporan warga {$laporan->nama_pelapor} ke Surveyor {$surveyor->name}", $id);

        return redirect()->route('admin.laporan-warga')->with('success', 'LAPORAN BERHASIL DITUGASKAN KE SURVEYOR!');
    }

    public function destroyLaporanWarga($id)
    {
        $laporan = LaporanWarga::findOrFail($id);
        
        // Hapus foto jika ada
        if ($laporan->foto && Storage::disk('public')->exists($laporan->foto)) {
            Storage::disk('public')->delete($laporan->foto);
        }

        $nama = $laporan->nama_pelapor;
        $laporan->delete();

        $this->logActivity('laporan', "Menghapus laporan warga dari: {$nama}");

        return redirect()->route('admin.laporan-warga')->with('success', 'LAPORAN WARGA BERHASIL DIHAPUS.');
    }

    public function createFromLaporan($id)
    {
        $laporan = LaporanWarga::findOrFail($id);
        
        if ($laporan->id_infrastruktur) {
            return redirect()->route('admin.laporan-warga')->with('error', 'Laporan ini sudah dikonversi menjadi aset infrastruktur.');
        }

        $semuaKecamatan = DB::table('kecamatan')->get();
        $semuaKelurahan = DB::table('kelurahan')->get();
        
        return view('admin.create-from-laporan', compact('laporan', 'semuaKecamatan', 'semuaKelurahan'));
    }

    public function storeFromLaporan(Request $request, $id)
    {
        $laporan = LaporanWarga::findOrFail($id);
        
        if ($laporan->id_infrastruktur) {
            return redirect()->route('admin.laporan-warga')->with('error', 'Laporan ini sudah dikonversi menjadi aset infrastruktur.');
        }

        $request->validate([
            'nama_infrastruktur' => 'required|string|max:255',
            'jenis' => 'required|string|in:jalan,titian,jembatan',
            'id_kecamatan' => 'required|exists:kecamatan,id_kecamatan',
            'id_kelurahan' => 'required|exists:kelurahan,id_kelurahan',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'material_eksisting' => 'required|string',
            'panjang' => 'required|numeric',
            'lebar' => 'required|numeric',
            'kondisi' => 'required|string',
        ]);

        $namaFoto = 'default.jpg';
        
        // Salin foto dari laporan warga ke folder infrastruktur
        if ($laporan->foto && Storage::disk('public')->exists($laporan->foto)) {
            $fileExtension = pathinfo($laporan->foto, PATHINFO_EXTENSION);
            $newFilename = time() . '_laporan_' . $laporan->id . '.' . $fileExtension;
            
            if (!Storage::disk('public')->exists('infrastruktur')) {
                Storage::disk('public')->makeDirectory('infrastruktur');
            }
            
            Storage::disk('public')->copy($laporan->foto, 'infrastruktur/' . $newFilename);
            $namaFoto = 'infrastruktur/' . $newFilename;
        }

        $infra = Infrastruktur::create([
            'id_user' => auth()->id(), 
            'id_kelurahan' => $request->id_kelurahan,
            'jenis' => strtolower($request->jenis),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'kondisi' => $request->kondisi, 
            'material_eksisting' => $request->material_eksisting,
            'panjang' => $request->panjang,
            'lebar' => $request->lebar,
            'has_drainase' => $request->has_drainase ?? 'tidak',
            'has_gorong_gorong' => $request->has_gorong_gorong ?? 'tidak',
            'foto_terbaru' => $namaFoto,
            'nama_objek' => $request->nama_infrastruktur, 
            'tgl_survey' => now(),
        ]);

        if ($namaFoto != 'default.jpg') {
            $this->processCnnAnalysis($infra->id_infrastruktur, $namaFoto);
        }

        // Update laporan warga
        $laporan->update([
            'id_infrastruktur' => $infra->id_infrastruktur,
            'status' => 'Diproses'
        ]);

        $this->logActivity('survey', "Konversi Laporan Warga ({$laporan->nama_pelapor}) ke Aset Baru: {$infra->nama_objek}", $infra->id_infrastruktur);

        return redirect()->route('admin.infrastruktur')->with('success', 'LAPORAN WARGA BERHASIL DIKONVERSI MENJADI ASET INFRASTRUKTUR!');
    }

    public function exportPdfLaporan()
    {
        $laporanWarga = LaporanWarga::orderBy('created_at', 'desc')->get();
        $this->logActivity('laporan', "Ekspor data Laporan Warga ke format PDF");
        
        $pdf = Pdf::loadView('admin.pdf-laporan-warga', compact('laporanWarga'))->setPaper('a4', 'landscape');
        return $pdf->download('Laporan_Warga_SINFRA_' . date('Ymd') . '.pdf');
    }

    public function exportExcelLaporan()
    {
        $laporanWarga = LaporanWarga::orderBy('created_at', 'desc')->get();
        $this->logActivity('laporan', "Ekspor data Laporan Warga ke format Excel");

        $headers = [
            "Content-type"        => "application/vnd.ms-excel; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=Rekap_Laporan_Warga_" . date('Y-m-d') . ".xls",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($laporanWarga) {
            echo '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
            echo '<head><meta charset="utf-8">';
            echo '<style>table, th, td { border: 0.5pt solid black; border-collapse: collapse; text-align: left; vertical-align: top; }</style>';
            echo '</head><body>';
            
            echo '<table>';
            echo '<tr><th colspan="8" style="text-align:center; font-size:16px; font-weight:bold; border:none; padding-bottom:10px;">REKAPITULASI LAPORAN WARGA (SINFRA)</th></tr>';
            
            echo '<tr style="background-color: #f3f4f6;">';
            echo '<th>NO</th>';
            echo '<th>TANGGAL</th>';
            echo '<th>PELAPOR</th>';
            echo '<th>NO HP</th>';
            echo '<th>DESKRIPSI</th>';
            echo '<th>PREDIKSI AI</th>';
            echo '<th>KONDISI</th>';
            echo '<th>STATUS</th>';
            echo '</tr>';

            $no = 1;
            foreach ($laporanWarga as $lap) {
                echo '<tr>';
                echo '<td style="text-align: center;">' . $no++ . '</td>';
                echo '<td>' . $lap->created_at->format('d/m/Y H:i') . '</td>';
                echo '<td>' . htmlspecialchars($lap->nama_pelapor) . '</td>';
                echo '<td>' . htmlspecialchars($lap->no_hp) . '</td>';
                echo '<td>' . htmlspecialchars($lap->deskripsi) . '</td>';
                echo '<td>' . htmlspecialchars(ucfirst($lap->jenis_ai)) . '</td>';
                echo '<td>' . htmlspecialchars($lap->label_ai) . '</td>';
                echo '<td>' . htmlspecialchars($lap->status) . '</td>';
                echo '</tr>';
            }
            
            echo '</table>';
            echo '</body></html>';
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportCsvInfrastruktur()
    {
        $infrastruktur = Infrastruktur::orderBy('created_at', 'desc')->get();
        $this->logActivity('infrastruktur', "Ekspor data Infrastruktur ke format CSV");

        $filename = "Infrastruktur_SINFRA_" . date('Ymd') . ".csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'NAMA', 'JENIS', 'KONDISI', 'KELURAHAN', 'LATITUDE', 'LONGITUDE', 'STATUS PERBAIKAN'];

        $callback = function() use($infrastruktur, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($infrastruktur as $inf) {
                fputcsv($file, array(
                    $inf->id_infrastruktur,
                    $inf->nama_infrastruktur,
                    ucfirst($inf->jenis),
                    $inf->kondisi,
                    $inf->kelurahan,
                    $inf->latitude,
                    $inf->longitude,
                    $inf->status_perbaikan
                ));
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ==========================================================
    // MODUL PROFIL ADMIN
    // ==========================================================

    public function profile()
    {
        $user = auth()->user();
        return view('admin.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = User::find(auth()->id());

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'profile_photo' => 'nullable|max:2048'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $user->profile_photo = $path;
        }

        $user->save();
        
        $this->logActivity('user', "Memperbarui profil akun");

        return redirect()->route('admin.profile')->with('success', 'PROFIL BERHASIL DIPERBARUI!');
    }

    // ==========================================================
    // MODUL LOG AKTIVITAS & BACKUP
    // ==========================================================

    public function activity(Request $request)
    {
        $activities = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.activity', compact('activities'));
    }

    public function exportActivityExcel()
    {
        $activities = \App\Models\ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        $headers = [
            "Content-type"        => "application/vnd.ms-excel; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=Log_Aktivitas_" . date('Y-m-d') . ".xls",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($activities) {
            $html = '<html><head><meta charset="UTF-8"></head><body>';
            $html .= '<table style="border-collapse: collapse; font-family: Calibri, sans-serif; font-size: 11pt;">';
            $html .= '<thead><tr>';
            $columns = ['ID', 'Waktu', 'Pengguna', 'Kategori', 'Aktivitas', 'IP Address'];
            
            foreach ($columns as $col) {
                $html .= '<th style="background-color: #1e1b4b; color: #ffffff; font-weight: bold; text-align: center; border: 1pt solid #000000; padding: 5px;">' . $col . '</th>';
            }
            $html .= '</tr></thead><tbody>';

            foreach ($activities as $log) {
                $html .= '<tr>';
                $html .= '<td style="border: 0.5pt solid #000000; padding: 5px; text-align: center;">' . $log->id . '</td>';
                $html .= '<td style="border: 0.5pt solid #000000; padding: 5px; text-align: center;">' . $log->created_at->format('Y-m-d H:i:s') . '</td>';
                $html .= '<td style="border: 0.5pt solid #000000; padding: 5px;">' . ($log->user ? $log->user->name : 'Sistem') . '</td>';
                $html .= '<td style="border: 0.5pt solid #000000; padding: 5px; text-align: center;">' . $log->module . '</td>';
                $html .= '<td style="border: 0.5pt solid #000000; padding: 5px;">' . $log->activity . '</td>';
                $html .= '<td style="border: 0.5pt solid #000000; padding: 5px; text-align: center;">' . $log->ip_address . '</td>';
                $html .= '</tr>';
            }
            $html .= '</tbody></table></body></html>';

            echo $html;
        };

        return response()->stream($callback, 200, $headers);
    }

    public function backupDatabase()
    {
        // Fitur ekspor sederhana yang mengambil semua data dari tabel-tabel utama
        // Ini adalah fallback aman agar bisa berjalan di OS Windows (Laragon) tanpa ketergantungan utility CLI
        
        $tables = [
            'users',
            'kecamatan',
            'kelurahan',
            'infrastruktur',
            'analisis_ai',
            'citra_cnn',
            'activity_logs'
        ];

        $sqlDump = "-- GEO-SINFRA DATABASE BACKUP\n";
        $sqlDump .= "-- Generated at: " . now()->format('Y-m-d H:i:s') . "\n\n";

        // Nonaktifkan foreign key checks saat import
        $sqlDump .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tables as $table) {
            $data = DB::table($table)->get();
            if ($data->isEmpty()) continue;

            $sqlDump .= "-- Table structure and data for `$table`\n";
            $sqlDump .= "TRUNCATE TABLE `$table`;\n"; // Hapus data lama jika direstore

            foreach ($data as $row) {
                // Konversi objek row ke array
                $rowArray = (array) $row;
                $keys = array_keys($rowArray);
                
                // Amankan nilai
                $values = array_map(function($value) {
                    if (is_null($value)) {
                        return 'NULL';
                    }
                    $value = addslashes($value);
                    $value = str_replace("\n", "\\n", $value);
                    $value = str_replace("\r", "\\r", $value);
                    return "'" . $value . "'";
                }, array_values($rowArray));

                $keysString = implode("`, `", $keys);
                $valuesString = implode(", ", $values);

                $sqlDump .= "INSERT INTO `$table` (`$keysString`) VALUES ($valuesString);\n";
            }
            $sqlDump .= "\n";
        }

        // Aktifkan kembali foreign key checks
        $sqlDump .= "SET FOREIGN_KEY_CHECKS=1;\n";

        $filename = "geo-sinfra_backup_" . date('Y-m-d_H-i-s') . ".sql";
        $headers = [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $this->logActivity('system', "Melakukan backup database");

        return response($sqlDump, 200, $headers);
    }

    /**
     * Menampilkan Halaman Simulasi AI
     */
    public function simulasiAi()
    {
        return view('admin.simulasi-ai');
    }
}