<?php

namespace App\Http\Controllers\TimTeknis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Infrastruktur;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class TimTeknisController extends Controller
{
    public function index()
    {
        $totalInfrastruktur = Infrastruktur::whereNull('deleted_at')->count();

        // ✅ FIX: Gunakan data dari tabel analisis_ai (hasil prediksi AI),
        //         bukan kolom 'kondisi' (input manual surveyor) agar konsisten dengan Admin Dashboard
        $aiData = DB::table('analisis_ai')
            ->join('infrastruktur', 'analisis_ai.id_infrastruktur', '=', 'infrastruktur.id_infrastruktur')
            ->whereNull('infrastruktur.deleted_at')
            ->select('analisis_ai.label_prioritas')
            ->get();

        $totalRusakBerat  = $aiData->where('label_prioritas', 'Rusak Berat')->count();
        $totalRusakSedang = $aiData->where('label_prioritas', 'Rusak Sedang')->count();
        $totalBaik        = $aiData->where('label_prioritas', 'Baik')->count();

        $totalPending   = Infrastruktur::where('status_verifikasi', 'Verified')->where('status_validasi', 'Pending')->count();
        $totalPerbaikan = Infrastruktur::where('status_perbaikan', 'Proses Perbaikan')->count();

        $recentReports = Infrastruktur::with(['kelurahan.kecamatan', 'user', 'analisis'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('tim_teknis.dashboard', compact(
            'totalInfrastruktur',
            'totalRusakBerat',
            'totalRusakSedang',
            'totalBaik',
            'totalPending',
            'totalPerbaikan',
            'recentReports'
        ));
    }
    public function monitoring()
    {
        // Tampilkan semua data di peta monitoring utama
        $infrastruktur = Infrastruktur::with(['kelurahan', 'user', 'analisis', 'cnn'])
            ->where('status_verifikasi', 'Verified')
            ->get();
            
        $kecamatan = \App\Models\Kecamatan::all();
        $kelurahan = \App\Models\Kelurahan::with('kecamatan')->get();
        
        return view('tim_teknis.monitoring', compact('infrastruktur', 'kecamatan', 'kelurahan'));
    }

    public function prioritas()
    {
        $prioritas = Infrastruktur::with(['kelurahan.kecamatan', 'user', 'analisis', 'cnn'])
            ->where(function ($q) {
                $q->where('kondisi', 'LIKE', '%Berat%')
                  ->orWhereHas('analisis', function ($sq) {
                      $sq->where('label_prioritas', 'LIKE', '%Berat%');
                  });
            })
            ->where('status_verifikasi', 'Verified')
            ->where('status_perbaikan', '!=', 'Selesai')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('tim_teknis.prioritas', compact('prioritas'));
    }


    public function show($id)
    {
        $infrastruktur = Infrastruktur::with(['kelurahan.kecamatan', 'user', 'analisis', 'cnn'])
            ->findOrFail($id);
        return view('tim_teknis.show', compact('infrastruktur'));
    }

    public function validasi(Request $request)
    {
        $counts = [
            'pending' => \App\Models\Infrastruktur::where('status_verifikasi', 'Verified')->where('status_validasi', 'Pending')->count(),
            'verified' => \App\Models\Infrastruktur::where('status_verifikasi', 'Verified')->where('status_validasi', 'Validated')->count(),
            'rejected' => \App\Models\Infrastruktur::where('status_verifikasi', 'Verified')->where('status_validasi', 'Rejected')->count(),
        ];
        return view('tim_teknis.validasi', compact('counts'));
    }

    public function prosesValidasi(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Validated,Rejected',
            'alasan_penolakan' => 'nullable|string'
        ]);

        $infra = Infrastruktur::findOrFail($id);
        $infra->status_validasi = $request->status;
        $infra->alasan_penolakan = $request->alasan_penolakan; // Disimpan sebagai catatan (baik diterima/ditolak)
        $infra->save();

        // Rekam Aktivitas Log
        $logCatatan = $request->alasan_penolakan ? " dengan catatan: {$request->alasan_penolakan}" : "";
        $logAction = $request->status == 'Validated' ? "menyetujui (Validasi)" : "menolak (Validasi)";
        \App\Models\ActivityLog::record("Tim Teknis {$logAction} laporan infrastruktur{$logCatatan}", 'infrastruktur', $infra->id_infrastruktur);

        // Kirim Notifikasi WA ke Surveyor
        \App\Services\WhatsAppService::sendValidationResultNotification($infra);

        $message = $request->status == 'Validated' ? 'Data berhasil divalidasi dan dipindahkan ke tab Disetujui!' : 'Data telah ditolak dan dipindahkan ke tab Ditolak!';
        return redirect()->route('tim_teknis.validasi', ['status' => $request->status])->with('success', $message);
    }

    public function updateStatusPerbaikan(Request $request, $id)
    {
        $request->validate([
            'status_perbaikan' => 'required|in:Menunggu,Proses Perbaikan,Selesai'
        ]);

        $infra = Infrastruktur::findOrFail($id);
        
        // Hanya bisa diubah jika status_validasi == Validated
        if ($infra->status_validasi !== 'Validated') {
            return redirect()->back()->with('error', 'Infrastruktur belum divalidasi (diterima). Tidak bisa mengubah status pengerjaan.');
        }

        $infra->status_perbaikan = $request->status_perbaikan;
        $infra->save();

        return redirect()->back()->with('success', 'Status pengerjaan fisik berhasil diperbarui menjadi: ' . $request->status_perbaikan);
    }

    public function profile()
    {
        $user = auth()->user();
        return view('tim_teknis.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = \App\Models\User::find(auth()->id());

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'profile_photo' => 'nullable|max:2048'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_photo);
            }
            $user->profile_photo = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        $user->save();

        return redirect()->route('tim_teknis.dashboard')->with('success', 'Profil Anda berhasil diperbarui!');
    }

    public function laporan(Request $request)
    {
        $aiData = DB::table('analisis_ai')
            ->join('infrastruktur', 'analisis_ai.id_infrastruktur', '=', 'infrastruktur.id_infrastruktur')
            ->whereNull('infrastruktur.deleted_at')
            ->select('analisis_ai.label_prioritas')
            ->get();

        $totalBaik        = $aiData->where('label_prioritas', 'Baik')->count();
        $totalRusakSedang = $aiData->where('label_prioritas', 'Rusak Sedang')->count();
        $totalRusakBerat  = $aiData->where('label_prioritas', 'Rusak Berat')->count();
        $totalData        = $aiData->count();

        return view('tim_teknis.laporan', compact(
            'totalBaik', 'totalRusakSedang', 'totalRusakBerat', 'totalData'
        ));
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
            
        if (!$inf) return redirect()->route('tim_teknis.monitoring')->with('error', 'ASET TIDAK DITEMUKAN.');
        
        $pdf = Pdf::loadView('admin.pdf-infrastruktur', compact('inf'))
            ->setOptions([
                'isPhpEnabled'    => true,
                'dpi'             => 150,
                'defaultFont'     => 'Helvetica',
            ])
            ->setPaper('A4', 'portrait');

        return $pdf->stream('Laporan_Infrastruktur_' . str_replace(' ', '_', $inf->nama_objek ?? 'Aset') . '.pdf');
    }
}
