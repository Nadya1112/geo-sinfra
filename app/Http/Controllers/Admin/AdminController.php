<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wilayah;
use App\Models\Infrastruktur;
use App\Models\AnalisisAi;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Menampilkan Dashboard Utama (Statistik)
     */
    public function index()
    {
        // 1. Statistik User
        $jumlahSurveyor = User::where('role', 'surveyor')->count();
        $jumlahKabid = User::where('role', 'kabid')->count();
        
        // 2. Statistik Data Wilayah & Infrastruktur
        $jumlahWilayah = Wilayah::count();
        $jumlahInfrastruktur = Infrastruktur::count();
        
        // 3. Statistik Analisis AI
        // Gunakan model langsung (pastikan file app/Models/AnalisisAi.php sudah benar)
        $jumlahAnalisis = AnalisisAi::count(); 
        
        // Jika masih error Class Not Found, gunakan baris di bawah ini sebagai cadangan:
        // $jumlahAnalisis = \Illuminate\Support\Facades\DB::table('analisis_ai')->whereNull('deleted_at')->count();

        return view('admin.dashboard', compact(
            'jumlahSurveyor', 
            'jumlahKabid', 
            'jumlahWilayah', 
            'jumlahInfrastruktur',
            'jumlahAnalisis'
        ));
    }

    /**
     * Menampilkan Halaman Peta Spasial (GIS)
     * Mengambil data kecamatan dan warna dari database
     */
    public function peta()
    {
        // Mengambil semua data wilayah (termasuk kolom 'warna' dan 'geojson_data'/'koordinat')
        $dataWilayah = Wilayah::all();

        return view('admin.peta', compact('dataWilayah'));
    }
}