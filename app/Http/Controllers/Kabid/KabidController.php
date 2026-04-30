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
        $infrastruktur = Infrastruktur::with(['kecamatan', 'user'])->get();
        $kecamatan = \App\Models\Kecamatan::all();
        
        return view('kabid.monitoring', compact('infrastruktur', 'kecamatan'));
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
}
