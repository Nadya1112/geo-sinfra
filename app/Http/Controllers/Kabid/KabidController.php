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
        $totalPending = Infrastruktur::where('status_verifikasi', 'Pending')->count();
        $totalVerified = Infrastruktur::where('status_verifikasi', 'Verified')->count();
        $totalInfrastruktur = Infrastruktur::count();
        
        $recentReports = Infrastruktur::with('user')
            ->where('status_verifikasi', 'Pending')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('kabid.dashboard', compact('totalPending', 'totalVerified', 'totalInfrastruktur', 'recentReports'));
    }
}
