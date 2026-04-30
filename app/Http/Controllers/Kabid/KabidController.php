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
}
