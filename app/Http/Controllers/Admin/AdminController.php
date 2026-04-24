<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Infrastruktur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Menampilkan Dashboard Utama (Statistik)
     */
    public function index()
    {
        // 1. Statistik User berdasarkan Role
        $jumlahSurveyor = User::where('role', 'surveyor')->count();
        $jumlahKabid = User::where('role', 'kabid')->count();
        
        // 2. Statistik Data Geografis & Fisik
        // Menggunakan tabel kecamatan karena tabel wilayah sudah dihapus
        $jumlahWilayah = DB::table('kecamatan')->count();
        $jumlahInfrastruktur = Infrastruktur::count();
        
        // 3. Statistik Analisis AI (Jalur Aman menggunakan DB Table)
        // Menghitung data yang belum dihapus (soft delete)
        $jumlahAnalisis = DB::table('analisis_ai')
            ->whereNull('deleted_at')
            ->count();

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
     * Mengambil data poligon kecamatan dan titik-titik infrastruktur
     */
    public function peta()
    {
        // 1. Ambil data poligon dari tabel kecamatan untuk background peta
        $semuaWilayah = DB::table('kecamatan')
            ->whereNull('deleted_at')
            ->get();

        // 2. Ambil data titik koordinat dari tabel infrastruktur untuk marker
        $dataInfrastruktur = DB::table('infrastruktur')
            ->whereNull('deleted_at')
            ->get();

        return view('admin.peta', compact('semuaWilayah', 'dataInfrastruktur'));
    }
    public function users()
{
    // Mengambil data pengguna dengan role selain admin
    $users = User::whereIn('role', ['surveyor', 'kabid'])->get();
    
    return view('admin.users', compact('users'));
}
}