<?php

namespace App\Http\Controllers\Kabid;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Infrastruktur;
use Illuminate\Support\Facades\DB;

class KabidController extends Controller
{
    public function index()
    {
        $totalInfrastruktur = Infrastruktur::count();
        $totalRusakBerat = Infrastruktur::where('kondisi', 'Rusak Berat')->count();
        $totalPrioritas = Infrastruktur::where('status_verifikasi', 'Pending')
            ->where('kondisi', 'Rusak Berat')
            ->count();
        
        $recentReports = Infrastruktur::with('user')
            ->where('status_verifikasi', 'Pending')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('kabid.dashboard', compact(
            'totalInfrastruktur', 
            'totalRusakBerat', 
            'totalPrioritas', 
            'recentReports'
        ));
    }
    public function monitoring()
    {
        // Tampilkan semua data di peta monitoring utama
        $infrastruktur = Infrastruktur::with(['kelurahan', 'user', 'analisis', 'cnn'])
            ->get();
            
        $kecamatan = \App\Models\Kecamatan::all();
        $kelurahan = \App\Models\Kelurahan::with('kecamatan')->get();
        
        return view('kabid.monitoring', compact('infrastruktur', 'kecamatan', 'kelurahan'));
    }

    public function verifikasi(\Illuminate\Http\Request $request)
    {
        $query = Infrastruktur::with(['kelurahan', 'user', 'analisis', 'cnn'])
            ->orderBy('created_at', 'desc');
            
        if ($request->get('show') == 'all') {
            $allUsulan = $query->get();
        } else {
            $allUsulan = $query->paginate(10)->withQueryString();
        }
            
        $counts = [
            'pending' => Infrastruktur::where('status_verifikasi', 'Pending')->count(),
            'verified' => Infrastruktur::where('status_verifikasi', 'Verified')->count(),
            'rejected' => Infrastruktur::where('status_verifikasi', 'Rejected')->count(),
        ];

        return view('kabid.verifikasi', compact('allUsulan', 'counts'));
    }

    public function show($id)
    {
        $infrastruktur = Infrastruktur::with(['kelurahan.kecamatan', 'user', 'analisis', 'cnn'])
            ->findOrFail($id);
        return view('kabid.show', compact('infrastruktur'));
    }

    public function prosesVerifikasi(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Verified,Rejected',
            'catatan' => 'nullable|string|max:500'
        ]);

        $infra = Infrastruktur::findOrFail($id);
        $infra->status_verifikasi = $request->status;
        // Jika ada kolom catatan verifikasi di database bisa ditambahkan di sini
        $infra->save();

        $message = $request->status == 'Verified' ? 'Usulan berhasil diverifikasi!' : 'Usulan telah ditolak.';
        return redirect()->back()->with('success', $message);
    }

    public function validasi(Request $request)
    {
        $query = Infrastruktur::with(['kelurahan.kecamatan', 'user', 'analisis', 'cnn'])
            ->orderBy('created_at', 'desc');
            
        if ($request->get('show') == 'all') {
            $allUsulan = $query->get();
        } else {
            $allUsulan = $query->paginate(10)->withQueryString();
        }

        $counts = [
            'pending' => Infrastruktur::where('status_verifikasi', 'Pending')->count(),
            'verified' => Infrastruktur::where('status_verifikasi', 'Verified')->count(),
            'rejected' => Infrastruktur::where('status_verifikasi', 'Rejected')->count(),
        ];

        return view('kabid.validasi', compact('allUsulan', 'counts'));
    }

    public function prosesValidasi(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Verified,Rejected',
        ]);

        $infra = Infrastruktur::findOrFail($id);
        $infra->status_verifikasi = $request->status;
        $infra->save();

        $message = $request->status == 'Verified' ? 'Data berhasil di-ACC!' : 'Data telah ditolak.';
        return redirect()->back()->with('success', $message);
    }

    public function profile()
    {
        $user = auth()->user();
        return view('kabid.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = \App\Models\User::find(auth()->id());

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'profile_photo' => 'nullable|image|max:2048'
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

        return redirect()->route('kabid.dashboard')->with('success', 'Profil Anda berhasil diperbarui!');
    }

    public function statistikTahunan(Request $request)
    {
        $year = $request->get('year', date('Y'));
        
        // Ambil daftar tahun yang tersedia di database untuk dropdown
        $availableYears = DB::table('infrastruktur')
            ->select(DB::raw('YEAR(created_at) as year'))
            ->whereNull('deleted_at')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        if ($availableYears->isEmpty()) {
            $availableYears = collect([date('Y')]);
        }
        
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

        // Sebaran Kondisi per Kecamatan (Tahunan)
        $semuaKecamatan = DB::table('kecamatan')->get();
        $kondisiKecamatan = [];
        foreach($semuaKecamatan as $kec) {
            $infraKec = DB::table('infrastruktur')
                ->leftJoin('kelurahan', 'infrastruktur.id_kelurahan', '=', 'kelurahan.id_kelurahan')
                ->where('kelurahan.id_kecamatan', $kec->id_kecamatan)
                ->whereYear('infrastruktur.created_at', $year)
                ->whereNull('infrastruktur.deleted_at')
                ->select(
                    DB::raw("COUNT(CASE WHEN LOWER(kondisi) LIKE '%baik%' THEN 1 END) as baik"),
                    DB::raw("COUNT(CASE WHEN LOWER(kondisi) LIKE '%ringan%' THEN 1 END) as ringan"),
                    DB::raw("COUNT(CASE WHEN LOWER(kondisi) LIKE '%sedang%' THEN 1 END) as sedang"),
                    DB::raw("COUNT(CASE WHEN LOWER(kondisi) LIKE '%berat%' THEN 1 END) as berat"),
                    DB::raw("COUNT(*) as total_semua")
                )
                ->first();
            
            $kondisiKecamatan[] = [
                'nama' => $kec->nama_kecamatan,
                'baik' => $infraKec->baik ?? 0,
                'ringan' => $infraKec->ringan ?? 0,
                'sedang' => $infraKec->sedang ?? 0,
                'berat' => $infraKec->berat ?? 0,
                'total' => $infraKec->total_semua ?? 0
            ];
        }

        return view('kabid.statistik-tahunan', compact('chartData', 'statsJenis', 'kondisiKecamatan', 'year', 'availableYears'));
    }

    public function laporan(Request $request)
    {
        $query = Infrastruktur::with(['kelurahan.kecamatan', 'user']);

        // Filter
        if ($request->kecamatan) {
            $query->whereHas('kelurahan', function($q) use ($request) {
                $q->where('id_kecamatan', $request->kecamatan);
            });
        }
        if ($request->kondisi) {
            $query->where('kondisi', $request->kondisi);
        }
        if ($request->jenis) {
            $query->where('jenis', strtolower($request->jenis));
        }
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        $query = $query->orderBy('created_at', 'desc');
        
        if ($request->get('show') == 'all') {
            $reports = $query->get();
        } else {
            $reports = $query->paginate(10)->withQueryString();
        }
        
        $kecamatan = \App\Models\Kecamatan::all();

        return view('kabid.laporan', compact('reports', 'kecamatan'));
    }
}
