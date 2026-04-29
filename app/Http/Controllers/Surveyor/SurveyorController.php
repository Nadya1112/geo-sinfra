<?php

namespace App\Http\Controllers\Surveyor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Infrastruktur;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SurveyorController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $totalSurvey = Infrastruktur::where('id_user', $userId)->count();
        // Hitung status nyata
        $waitingValidation = Infrastruktur::where('id_user', $userId)->where('status_verifikasi', 'Pending')->count();
        $verifiedAI = Infrastruktur::where('id_user', $userId)->where('status_verifikasi', 'Verified')->count();

        // Ambil 5 upload terakhir
        $recentUploads = Infrastruktur::where('id_user', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('surveyor.dashboard', compact('totalSurvey', 'waitingValidation', 'verifiedAI', 'recentUploads'));
    }

    public function create()
    {
        $semuaKecamatan = DB::table('kecamatan')->get();
        return view('surveyor.input', compact('semuaKecamatan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_infrastruktur' => 'required|string|max:255',
            'jenis_infrastruktur' => 'required|in:Jalan,Jembatan,Drainase',
            'id_kecamatan' => 'required|exists:kecamatan,id_kecamatan',
            'latitude' => 'required',
            'longitude' => 'required',
            'foto' => 'required|image|max:5120', // Max 5MB
        ]);

        $path = $request->file('foto')->store('infrastruktur', 'public');

        Infrastruktur::create([
            'id_user' => auth()->id(),
            'nama_infrastruktur' => $request->nama_infrastruktur,
            'jenis_infrastruktur' => $request->jenis_infrastruktur,
            'id_kecamatan' => $request->id_kecamatan,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'foto_terbaru' => $path,
            'kondisi' => 'Baik', // Default awal
            'status_verifikasi' => 'Pending',
        ]);

        return redirect()->route('surveyor.history')->with('success', 'Data berhasil dikirim!');
    }

    public function history()
    {
        $riwayat = Infrastruktur::where('id_user', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
        return view('surveyor.history', compact('riwayat'));
    }

    public function map()
    {
        $dataMap = Infrastruktur::where('id_user', auth()->id())->get();
        return view('surveyor.map', compact('dataMap'));
    }

    public function profile()
    {
        $user = auth()->user();
        return view('surveyor.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = User::find(auth()->id());

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'profile_photo' => 'nullable|image|max:2048'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $user->profile_photo = $path;
        }

        $user->save();

        return redirect()->route('surveyor.dashboard')->with('success', 'Profil Anda berhasil diperbarui!');
    }
}
