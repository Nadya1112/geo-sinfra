<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Infrastruktur;
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
        return view('admin.dashboard');
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

        return redirect()->back()->with('success', "Aset {$infra->nama_objek} berhasil diverifikasi.");
    }

    private function logActivity($type, $description, $referenceId = null)
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'type' => $type,
            'description' => $description,
            'reference_id' => $referenceId
        ]);
    }

    /**
     * Menampilkan Pusat Statistik dan Laporan (SINFRA)
     */
    public function statistik()
    {
        $jumlahSurveyor = User::where('role', 'surveyor')->count();
        $jumlahKabid = User::where('role', 'kabid')->count();
        $jumlahWilayah = DB::table('kecamatan')->count();
        $jumlahInfrastruktur = Infrastruktur::whereNull('deleted_at')->count();
        
        // Menghitung jumlah data yang sudah dianalisis AI
        $jumlahAnalisis = DB::table('analisis_ai')->count();

        // Data Kondisi BERDASARKAN HASIL AI (Agar lebih akurat untuk laporan TA)
        $hasilAi = DB::table('analisis_ai')->get();
        $jumlahRusakBerat = $hasilAi->where('label_prioritas', 'Rusak Berat')->count();
        $jumlahRusakSedang = $hasilAi->where('label_prioritas', 'Rusak Sedang')->count(); 
        $jumlahRusakRingan = $hasilAi->where('label_prioritas', 'Rusak Ringan')->count();
        $jumlahBaik = $jumlahInfrastruktur - ($jumlahRusakBerat + $jumlahRusakSedang + $jumlahRusakRingan);

        $recentActivities = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.statistik', compact(
            'jumlahSurveyor', 'jumlahKabid', 'jumlahWilayah', 'jumlahInfrastruktur', 
            'jumlahAnalisis', 'jumlahRusakBerat', 'jumlahRusakSedang', 'jumlahRusakRingan', 'jumlahBaik',
            'recentActivities'
        ));
    }

    /**
     * Menampilkan Statistik Tahunan Berbasis Batang (Bar Chart) & Rekapitulasi Wilayah AI
     */
    public function statistikTahunan()
    {
        $year = date('Y');
        
        // Data Perbulan (Jan - Des)
        $monthlyData = DB::table('infrastruktur')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as total'))
            ->whereYear('created_at', $year)
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
            ->whereYear('created_at', $year)
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
                ->whereYear('infrastruktur.created_at', $year)
                ->whereNull('infrastruktur.deleted_at')
                ->select(
                    DB::raw("COUNT(CASE WHEN LOWER(analisis_ai.label_prioritas) LIKE '%baik%' THEN 1 END) as baik"),
                    DB::raw("COUNT(CASE WHEN LOWER(analisis_ai.label_prioritas) LIKE '%ringan%' THEN 1 END) as ringan"),
                    DB::raw("COUNT(CASE WHEN LOWER(analisis_ai.label_prioritas) LIKE '%sedang%' THEN 1 END) as sedang"),
                    DB::raw("COUNT(CASE WHEN LOWER(analisis_ai.label_prioritas) LIKE '%berat%' THEN 1 END) as berat"),
                    DB::raw("COUNT(*) as total_semua")
                )
                ->first();
            
            $kondisiKecamatan[] = [
                'name' => $kec->nama_kecamatan,
                'nama' => $kec->nama_kecamatan,
                'baik' => $infraKec->baik ?? 0,
                'ringan' => $infraKec->ringan ?? 0,
                'sedang' => $infraKec->sedang ?? 0,
                'berat' => $infraKec->berat ?? 0,
                'total' => $infraKec->total_semua ?? 0
            ];
        }

        return view('admin.statistik-tahunan', compact('chartData', 'statsJenis', 'kondisiKecamatan', 'year'));
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
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,surveyor',
            'id_kecamatan' => 'nullable|exists:kecamatan,id_kecamatan',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
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
        if ($user->role === 'kabid') return redirect()->route('admin.users')->with('error', 'AKUN KABID TERKUNCI.');
        
        $semuaWilayah = DB::table('kecamatan')->get();
        return view('admin.edit-user', compact('user', 'semuaWilayah'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,surveyor',
            'id_kecamatan' => 'nullable|exists:kecamatan,id_kecamatan',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
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
        if ($user->role === 'kabid') return redirect()->route('admin.users')->with('error', 'AKUN KABID DILINDUNGI.');
        if (auth()->id() == $user->id) return redirect()->route('admin.users')->with('error', 'TIDAK BISA MENGHAPUS AKUN SENDIRI.');

        $name = $user->name;
        $user->delete();

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
            ->select('kelurahan.*', 'kecamatan.nama_kecamatan');

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
                               ->select('infrastruktur.*', 'kelurahan.nama_kelurahan', 'kecamatan.nama_kecamatan', 'kelurahan.id_kecamatan');

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
            'jenis_infrastruktur' => 'required|string',
            'id_kecamatan' => 'required|exists:kecamatan,id_kecamatan',
            'id_kelurahan' => 'required|exists:kelurahan,id_kelurahan',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:5120',
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
            'jenis_infrastruktur' => $request->jenis_infrastruktur,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'kondisi' => $request->kondisi, 
            'material_eksisting' => $request->material_eksisting,
            'panjang' => $request->panjang,
            'lebar' => $request->lebar,
            'has_drainase' => $request->has_drainase ?? 'tidak',
            'foto_terbaru' => 'infrastruktur/' . $namaFoto,
            'nama_objek' => $request->nama_infrastruktur, 
            'jenis' => strtolower($request->jenis_infrastruktur),
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
        
        $pdf = Pdf::loadView('admin.pdf-infrastruktur', compact('inf'));
        $pdf->setPaper('A4', 'portrait');
        
        $fileName = 'Laporan_Infrastruktur_' . str_replace(' ', '_', ($inf->nama_objek ?? 'Export')) . '.pdf';
        return $pdf->download($fileName);
    }

    public function updateInfrastruktur(Request $request, $id)
    {
        $infra = Infrastruktur::findOrFail($id);
        
        $request->validate([
            'nama_infrastruktur' => 'required|string|max:255',
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
            'id_kelurahan' => $request->id_kelurahan,
            'jenis_infrastruktur' => $request->jenis_infrastruktur,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'kondisi' => $request->kondisi, 
            'material_eksisting' => $request->material_eksisting,
            'panjang' => $request->panjang,
            'lebar' => $request->lebar,
            'has_drainase' => $request->has_drainase,
            'foto_terbaru' => $namaFoto,
            'nama_objek' => $request->nama_infrastruktur, 
            'jenis' => strtolower($request->jenis_infrastruktur),
            'status_verifikasi' => $request->status_verifikasi ?? $infra->status_verifikasi,
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
        
        $infra->delete(); // Otomatis mengisi deleted_at melalui Laravel system

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
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
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

        $this->logActivity('profil', 'Memperbarui informasi profil pribadi');

        return redirect()->route('admin.dashboard')->with('success', 'Profil Anda berhasil diperbarui!');
    }
}