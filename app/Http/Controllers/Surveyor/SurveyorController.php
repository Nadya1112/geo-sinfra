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
        $waitingValidation = Infrastruktur::where('id_user', $userId)->where('status_verifikasi', 'Pending')->count();
        $verifiedAI = Infrastruktur::where('id_user', $userId)->where('status_verifikasi', 'Verified')->count();

        $recentUploads = Infrastruktur::where('id_user', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $semuaKecamatan = DB::table('kecamatan')->get();
        return view('surveyor.dashboard', compact('totalSurvey', 'waitingValidation', 'verifiedAI', 'recentUploads', 'semuaKecamatan'));
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

        // Jika belum memilih di profil, tampilkan semua agar bisa pilih pertama kali
        if ($semuaKecamatan->isEmpty()) {
            $semuaKecamatan = DB::table('kecamatan')->get();
        }

        $semuaKelurahan = DB::table('kelurahan')->get();
        return view('surveyor.input', compact('semuaKecamatan', 'semuaKelurahan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_infrastruktur' => 'required|string|max:255',
            'jenis_infrastruktur' => 'required',
            'id_kecamatan' => 'required|exists:kecamatan,id_kecamatan',
            'id_kelurahan' => 'required|exists:kelurahan,id_kelurahan',
            'latitude' => 'required',
            'longitude' => 'required',
            'foto' => 'required|image|max:5120',
        ]);

        $path = $request->file('foto')->store('infrastruktur', 'public');

        // Logic sync with Admin for mapping 'jenis'
        $jenisMapping = [
            'Jalan' => 'Jalan',
            'Jembatan' => 'Jembatan',
            'Drainase' => 'Drainase'
        ];
        $jenisEnum = $jenisMapping[$request->jenis_infrastruktur] ?? 'Lainnya';

        DB::table('infrastruktur')->insert([
            'id_user' => auth()->id(),
            'nama_infrastruktur' => $request->nama_infrastruktur,
            'nama_objek' => $request->nama_infrastruktur,
            'jenis_infrastruktur' => $request->jenis_infrastruktur,
            'jenis' => $jenisEnum,
            'id_kecamatan' => $request->id_kecamatan,
            'id_kelurahan' => $request->id_kelurahan,
            'latitude' => str_replace(',', '.', $request->latitude),
            'longitude' => str_replace(',', '.', $request->longitude),
            'kondisi' => 'Menunggu AI',
            'alamat' => $request->alamat ?? '-',
            'foto_terbaru' => $path,
            'status_verifikasi' => 'Pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('surveyor.history')->with('success', 'Data berhasil dikirim!');
    }

    public function history()
    {
        $riwayat = Infrastruktur::with(['kelurahan', 'analisis', 'cnn'])
            ->where('id_user', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
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
        $infrastruktur = Infrastruktur::with(['cnn', 'analisis'])
            ->where('id_infrastruktur', $id)
            ->where('id_user', auth()->id())
            ->firstOrFail();
            
        $user = auth()->user();
        $semuaKecamatan = $user->kecamatans;

        if ($semuaKecamatan->isEmpty()) {
            $semuaKecamatan = DB::table('kecamatan')->get();
        }

        $semuaKelurahan = DB::table('kelurahan')->get();
        return view('surveyor.edit', compact('infrastruktur', 'semuaKecamatan', 'semuaKelurahan'));
    }

    public function update(Request $request, $id)
    {
        $infrastruktur = Infrastruktur::where('id_infrastruktur', $id)->where('id_user', auth()->id())->firstOrFail();

        $request->validate([
            'nama_infrastruktur' => 'required|string|max:255',
            'jenis_infrastruktur' => 'required|string',
            'id_kecamatan' => 'required|exists:kecamatan,id_kecamatan',
            'id_kelurahan' => 'required|exists:kelurahan,id_kelurahan',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('infrastruktur', 'public');
            $infrastruktur->foto_terbaru = $path;
            $infrastruktur->kondisi = 'Menunggu AI';
            $infrastruktur->status_verifikasi = 'Pending';
        }

        $jenisEnum = strtolower($request->jenis_infrastruktur);
        $allowedEnum = ['jalan', 'drainase', 'jembatan', 'pju'];
        if (!in_array($jenisEnum, $allowedEnum)) {
            $jenisEnum = 'jalan';
        }

        $infrastruktur->nama_infrastruktur = $request->nama_infrastruktur;
        $infrastruktur->nama_objek = $request->nama_infrastruktur;
        $infrastruktur->jenis_infrastruktur = $request->jenis_infrastruktur;
        $infrastruktur->jenis = $jenisEnum;
        $infrastruktur->id_kecamatan = $request->id_kecamatan;
        $infrastruktur->id_kelurahan = $request->id_kelurahan;
        $infrastruktur->latitude = str_replace(',', '.', $request->latitude);
        $infrastruktur->longitude = str_replace(',', '.', $request->longitude);
        $infrastruktur->alamat = $request->alamat ?? '-';
        $infrastruktur->save();

        return redirect()->route('surveyor.history')->with('success', 'Data berhasil diperbarui!');
    }

    public function map()
    {
        $dataMap = Infrastruktur::with(['cnn', 'analisis'])->where('id_user', auth()->id())->get();
        return view('surveyor.map', compact('dataMap'));
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
            'profile_photo' => 'nullable|image|max:2048'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $user->profile_photo = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        $user->save();

        return redirect()->route('surveyor.dashboard')->with('success', 'Profil Anda berhasil diperbarui!');
    }
}
