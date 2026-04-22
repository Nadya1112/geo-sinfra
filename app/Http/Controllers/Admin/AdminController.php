<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
// use App\Models\Infrastruktur; // Aktifkan jika Model ini sudah ada

class AdminController extends Controller
{
    public function index()
    {
        // Mengambil data untuk statistik
        $totalSurveyor = User::where('role', 'surveyor')->count();
        $totalKabid = User::where('role', 'kabid')->count();
        
        // Contoh: $totalData = Infrastruktur::count();

        return view('admin.dashboard', compact('totalSurveyor', 'totalKabid'));
    }
}