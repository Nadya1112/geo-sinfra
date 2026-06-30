<?php

namespace App\Http\Controllers\Surveyor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Infrastruktur;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Traits\AiProcessingTrait;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class SurveyorController extends Controller
{
    use AiProcessingTrait;

    public function index()
    {
        $userId = auth()->id();
        $totalSurvey = Infrastruktur::where('id_user', $userId)->count();
        $waitingValidation = Infrastruktur::where('id_user', $userId)->where('status_verifikasi', 'Pending')->count();
        $verifiedAI = Infrastruktur::where('id_user', $userId)->where('status_verifikasi', 'Verified')->count();
        $totalRejected = Infrastruktur::where('id_user', $userId)->where('status_validasi', 'Rejected')->count();

        $totalTugas = \App\Models\LaporanWarga::where('id_surveyor', $userId)->count();
        $tugasMenunggu = \App\Models\LaporanWarga::where('id_surveyor', $userId)->where('status', 'Menunggu')->count();
        $tugasSelesai = \App\Models\LaporanWarga::where('id_surveyor', $userId)->where('status', 'Selesai')->count();

        $user = auth()->user();
        
        // Cek wilayah tugas (Prioritas pivot table, fallback ke kolom id_kecamatan)
        $kecamatans = $user->kecamatans;
        if ($kecamatans->isEmpty() && $user->id_kecamatan) {
            $userKec = \App\Models\Kecamatan::find($user->id_kecamatan);
            if ($userKec) {
                // Jangan push ke relation, cukup buat collection sementara
                $kecamatans = collect([$userKec]);
            }
        }

        $semuaKecamatan = \App\Models\Kecamatan::all();
        $recentUploads = Infrastruktur::where('id_user', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('surveyor.dashboard', compact('totalSurvey', 'waitingValidation', 'verifiedAI', 'totalRejected', 'totalTugas', 'tugasMenunggu', 'tugasSelesai', 'recentUploads', 'semuaKecamatan', 'kecamatans'));
    }

    public function updateTerritories(Request $request)
    {
        $request->validate([
            'id_kecamatan' => 'required|array',
            'id_kecamatan.*' => 'exists:kecamatan,id_kecamatan',
        ]);

        $user = User::find(auth()->id());
        $user->kecamatans()->sync($request->id_kecamatan);
        
        // Simpan id pertama untuk legacy support
        if (!empty($request->id_kecamatan)) {
            $user->id_kecamatan = $request->id_kecamatan[0];
            $user->save();
        }

        return back()->with('success', 'Wilayah tugas berhasil diperbarui.');
    }

    public function create()
    {
        $user = auth()->user();
        $semuaKecamatan = $user->kecamatans;

        // Fallback ke legacy column jika pivot kosong
        if ($semuaKecamatan->isEmpty() && $user->id_kecamatan) {
            $userKec = \App\Models\Kecamatan::find($user->id_kecamatan);
            if ($userKec) $semuaKecamatan = collect([$userKec]);
        }

        // Jika benar-benar belum ada wilayah tugas, tampilkan semua agar bisa pilih pertama kali
        if ($semuaKecamatan->isEmpty()) {
            $semuaKecamatan = \App\Models\Kecamatan::all();
            $semuaKelurahan = \App\Models\Kelurahan::all();
        } else {
            $kecamatanIds = $semuaKecamatan->pluck('id_kecamatan');
            $semuaKelurahan = \App\Models\Kelurahan::whereIn('id_kecamatan', $kecamatanIds)->get();
        }

        return view('surveyor.input', compact('semuaKecamatan', 'semuaKelurahan'));
    }

    /**
     * PROSES SIMPAN DATA LAPANGAN BARU (Eloquent Model Terintegrasi Observer AI)
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_infrastruktur' => 'required|string|max:255',
            'id_kecamatan' => 'required|exists:kecamatan,id_kecamatan',
            'id_kelurahan' => 'required|exists:kelurahan,id_kelurahan',
            'latitude' => 'required',
            'longitude' => 'required',
            'foto' => 'required|max:20480',
            'kondisi' => 'nullable|string', // 🌟 Menangkap input deskripsi kerusakan teks untuk Decision Tree
            'material_eksisting' => 'nullable|string',
            'panjang' => 'required|numeric',
            'lebar' => 'required|numeric',
            'has_drainase' => 'nullable|boolean',
            'has_gorong_gorong' => 'nullable|boolean',
            'rencana_perbaikan' => 'nullable|string',
            'tgl_survey' => 'nullable|date',
        ]);

        $namaFoto = 'default.jpg';
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $namaFoto = time() . '_' . $file->getClientOriginalName();
            
            $destinationPath = storage_path('app/public/infrastruktur');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            // Menggunakan fungsi native PHP untuk menghindari error php_fileinfo pada storeAs()
            move_uploaded_file($_FILES['foto']['tmp_name'], $destinationPath . '/' . $namaFoto);
        }

        // 🌟 DIUBAH KE ELOQUENT MODEL agar memicu fungsi saved() di InfrastrukturObserver otomatis
        $infra = Infrastruktur::create([
            'id_user' => auth()->id(),
            'nama_objek' => $request->nama_infrastruktur,
            'nama_infrastruktur' => $request->nama_infrastruktur,
            'jenis' => 'jalan',
            'jenis_infrastruktur' => 'jalan',
            'id_kelurahan' => $request->id_kelurahan,
            'latitude' => str_replace(',', '.', $request->latitude),
            'longitude' => str_replace(',', '.', $request->longitude),
            'kondisi' => $request->kondisi ?? '-', // Menyimpan teks riil lapangan (misal: "titian ulin retak dan goyang")
            'material_eksisting' => $request->material_eksisting ?? '-',
            'panjang' => $request->panjang,
            'lebar' => $request->lebar,
            'has_drainase' => $request->has('has_drainase') ? 'ya' : 'tidak',
            'has_gorong_gorong' => $request->has('has_gorong_gorong') ? 'ya' : 'tidak',
            'rencana_perbaikan' => $request->rencana_perbaikan,
            'tgl_survey' => $request->tgl_survey ?? now()->toDateString(),
            'foto_terbaru' => 'infrastruktur/' . $namaFoto,
            'status_verifikasi' => 'Pending',
        ]);

        // Memicu Analisis Visual CNN via API Python
        if ($request->hasFile('foto')) {
            $this->processCnnAnalysis($infra->id_infrastruktur, 'infrastruktur/' . $namaFoto);
        }

        return redirect()->route('surveyor.history')->with('success', 'Data lapangan berhasil disimpan & dianalisis otomatis oleh AI!');
    }

    public function history(\Illuminate\Http\Request $request)
    {
        $query = Infrastruktur::with(['kelurahan.kecamatan', 'analisis', 'cnn'])
            ->where('id_user', auth()->id())
            ->orderBy('created_at', 'desc');
            
        if ($request->has('status') && $request->get('status') != '') {
            $query->where('status_verifikasi', $request->get('status'));
        }
            
        if ($request->get('show') == 'all') {
            $riwayat = $query->get();
        } else {
            $riwayat = $query->paginate(10)->withQueryString();
        }

        return view('surveyor.history', compact('riwayat'));
    }

    public function show($id)
    {
        $infrastruktur = Infrastruktur::with(['kelurahan', 'cnn', 'analisis'])
            ->where('id_infrastruktur', $id)
            ->where('id_user', auth()->id())
            ->firstOrFail();
        return view('surveyor.show', compact('infrastruktur'));
    }

    public function edit($id)
    {
        $infrastruktur = Infrastruktur::with(['cnn', 'analisis', 'kelurahan'])
            ->where('id_infrastruktur', $id)
            ->where('id_user', auth()->id())
            ->firstOrFail();
            
        // Pastikan id_kecamatan terisi untuk filter di view
        if (!$infrastruktur->id_kecamatan && $infrastruktur->kelurahan) {
            $infrastruktur->id_kecamatan = $infrastruktur->kelurahan->id_kecamatan;
        }
            
        $user = auth()->user();
        $semuaKecamatan = $user->kecamatans;

        // Fallback ke legacy column jika pivot kosong
        if ($semuaKecamatan->isEmpty() && $user->id_kecamatan) {
            $userKec = \App\Models\Kecamatan::find($user->id_kecamatan);
            if ($userKec) $semuaKecamatan = collect([$userKec]);
        }

        // Jika surveyor tidak memiliki wilayah (baru daftar), tampilkan semua
        if ($semuaKecamatan->isEmpty()) {
            $semuaKecamatan = \App\Models\Kecamatan::all();
            $semuaKelurahan = \App\Models\Kelurahan::all();
        } else {
            // Gabungkan dengan kecamatan milik data jika tidak ada di list surveyor (agar data lama bisa diedit)
            if ($infrastruktur->id_kecamatan && !$semuaKecamatan->contains('id_kecamatan', $infrastruktur->id_kecamatan)) {
                $kecData = \App\Models\Kecamatan::find($infrastruktur->id_kecamatan);
                if ($kecData) $semuaKecamatan->push($kecData);
            }

            $kecamatanIds = $semuaKecamatan->pluck('id_kecamatan');
            $semuaKelurahan = \App\Models\Kelurahan::whereIn('id_kecamatan', $kecamatanIds)->get();
            
            // Pastikan kelurahan milik data juga masuk di list
            if ($infrastruktur->id_kelurahan && !$semuaKelurahan->contains('id_kelurahan', $infrastruktur->id_kelurahan)) {
                $kelData = \App\Models\Kelurahan::find($infrastruktur->id_kelurahan);
                if ($kelData) $semuaKelurahan->push($kelData);
            }
        }

        return view('surveyor.edit', compact('infrastruktur', 'semuaKecamatan', 'semuaKelurahan'));
    }

    /**
     * PROSES UPDATE DATA LAPANGAN (Memicu Kalkulasi Ulang Otak Decision Tree)
     */
    public function update(Request $request, $id)
    {
        $infrastruktur = Infrastruktur::where('id_infrastruktur', $id)->where('id_user', auth()->id())->firstOrFail();

        $request->validate([
            'nama_infrastruktur' => 'required|string|max:255',
            'id_kecamatan' => 'required|exists:kecamatan,id_kecamatan',
            'id_kelurahan' => 'required|exists:kelurahan,id_kelurahan',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'kondisi' => 'nullable|string', // 🌟 Kolom pembaruan teks kondisi kerusakan
            'foto' => 'nullable|max:20480',
            'material_eksisting' => 'nullable|string',
            'panjang' => 'required|numeric',
            'lebar' => 'required|numeric',
            'has_drainase' => 'nullable|boolean',
            'has_gorong_gorong' => 'nullable|boolean',
            'rencana_perbaikan' => 'nullable|string',
            'tgl_survey' => 'nullable|date',
        ]);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $namaFoto = time() . '_' . $file->getClientOriginalName();
            
            $destinationPath = storage_path('app/public/infrastruktur');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            // Native PHP upload bypasses php_fileinfo error
            move_uploaded_file($_FILES['foto']['tmp_name'], $destinationPath . '/' . $namaFoto);

            // Hapus foto lama jika ada
            if ($infrastruktur->foto_terbaru && file_exists(storage_path('app/public/' . $infrastruktur->foto_terbaru))) {
                unlink(storage_path('app/public/' . $infrastruktur->foto_terbaru));
            }
            $infrastruktur->foto_terbaru = 'infrastruktur/' . $namaFoto;
            
            // Analisis visual otomatis jika foto baru diunggah
            $this->processCnnAnalysis($infrastruktur->id_infrastruktur, $infrastruktur->foto_terbaru);
        }

        $infrastruktur->nama_objek = $request->nama_infrastruktur;
        $infrastruktur->nama_infrastruktur = $request->nama_infrastruktur;
        $infrastruktur->jenis = 'jalan';
        $infrastruktur->jenis_infrastruktur = 'jalan';
        $infrastruktur->id_kelurahan = $request->id_kelurahan;
        $infrastruktur->latitude = str_replace(',', '.', $request->latitude);
        $infrastruktur->longitude = str_replace(',', '.', $request->longitude);
        $infrastruktur->kondisi = $request->kondisi ?? '-'; // Menyimpan teks kondisi kerusakan terupdate
        $infrastruktur->material_eksisting = $request->material_eksisting ?? '-';
        $infrastruktur->panjang = $request->panjang;
        $infrastruktur->lebar = $request->lebar;
        $infrastruktur->has_drainase = $request->has('has_drainase') ? 'ya' : 'tidak';
        $infrastruktur->has_gorong_gorong = $request->has('has_gorong_gorong') ? 'ya' : 'tidak';
        $infrastruktur->rencana_perbaikan = $request->rencana_perbaikan;
        $infrastruktur->tgl_survey = $request->tgl_survey;
        
        // Kembalikan ke antrean Admin & Tim Teknis setelah direvisi Surveyor
        $infrastruktur->status_verifikasi = 'Pending';
        $infrastruktur->status_validasi = 'Pending';
        $infrastruktur->alasan_penolakan = null;
        
        // 🌟 Pemanggilan save() pada Eloquent model otomatis menyenggol Observer AI untuk hitung ulang skor
        $infrastruktur->save();

        // Jika ada foto baru, kirim ulang ke model visual Python CNN
        if ($request->hasFile('foto')) {
            $this->processCnnAnalysis($infrastruktur->id_infrastruktur, 'infrastruktur/' . $infrastruktur->foto_terbaru);
        }

        return redirect()->route('surveyor.history')->with('success', 'Data & hasil Analisis AI berhasil diperbarui!');
    }

    /**
     * HAPUS DATA INFRASTRUKTUR MILIK SURVEYOR SENDIRI
     * Hanya bisa dihapus jika status_verifikasi masih 'Pending' (belum diproses admin)
     */
    public function destroy($id)
    {
        $infrastruktur = Infrastruktur::where('id_infrastruktur', $id)
            ->where('id_user', auth()->id())
            ->firstOrFail();

        // Proteksi: tidak boleh hapus jika sudah diverifikasi atau divalidasi admin/tim_teknis
        if ($infrastruktur->status_verifikasi !== 'Pending') {
            return redirect()->route('surveyor.history')
                ->with('error', 'Data tidak dapat dihapus karena sudah diproses oleh Admin. Hubungi Admin untuk penghapusan.');
        }

        // Hapus foto dari storage jika bukan default
        if ($infrastruktur->foto_terbaru && $infrastruktur->foto_terbaru !== 'infrastruktur/default.jpg') {
            Storage::disk('public')->delete($infrastruktur->foto_terbaru);
        }

        $namaObjek = $infrastruktur->nama_objek;
        $infrastruktur->forceDelete(); // HardDelete via Eloquent

        return redirect()->route('surveyor.history')
            ->with('success', "Data \"$namaObjek\" berhasil dihapus.");
    }

    public function map()
    {
        $dataMap = Infrastruktur::with(['cnn', 'analisis', 'kelurahan.kecamatan'])->where('id_user', auth()->id())->get();
        $myKecamatans = auth()->user()->kecamatans;
        if ($myKecamatans->isEmpty()) {
            $myKecamatans = \App\Models\Kecamatan::all();
            $allKelurahans = \App\Models\Kelurahan::all();
        } else {
            $allKelurahans = \App\Models\Kelurahan::whereIn('id_kecamatan', $myKecamatans->pluck('id_kecamatan'))->get();
        }

        return view('surveyor.map', compact('dataMap', 'myKecamatans', 'allKelurahans'));
    }

    public function profile()
    {
        $user = auth()->user();
        $semuaKecamatan = DB::table('kecamatan')->get();
        return view('surveyor.profile', compact('user', 'semuaKecamatan'));
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
            if ($user->profile_photo && file_exists(storage_path('app/public/' . $user->profile_photo))) {
                unlink(storage_path('app/public/' . $user->profile_photo));
            }
            
            $file = $request->file('profile_photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            $destinationPath = storage_path('app/public/profile_photos');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            move_uploaded_file($_FILES['profile_photo']['tmp_name'], $destinationPath . '/' . $filename);
            $user->profile_photo = 'profile_photos/' . $filename;
        } 
        
        $user->save();

        return redirect()->route('surveyor.dashboard')->with('success', 'Profil Anda berhasil diperbarui!');
    }

    // ==========================================
    // MODUL PENUGASAN LAPORAN WARGA
    // ==========================================

    public function laporan(Request $request)
    {
        $idSurveyor = auth()->id();
        $search = $request->query('search');
        $status = $request->query('status');

        $query = \App\Models\LaporanWarga::where('id_surveyor', $idSurveyor);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_pelapor', 'LIKE', "%{$search}%")
                  ->orWhere('deskripsi', 'LIKE', "%{$search}%");
            });
        }

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $laporanWarga = $query->latest()->paginate(10)->withQueryString();
        
        return view('surveyor.laporan', compact('laporanWarga', 'search', 'status'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Menunggu,Ditinjau,Diproses,Selesai,Ditolak',
        ]);

        $laporan = \App\Models\LaporanWarga::where('id', $id)->where('id_surveyor', auth()->id())->firstOrFail();
        
        $laporan->update([
            'status' => $request->status,
        ]);

        return redirect()->route('surveyor.laporan')->with('success', 'STATUS PENUGASAN BERHASIL DIPERBARUI!');
    }
}