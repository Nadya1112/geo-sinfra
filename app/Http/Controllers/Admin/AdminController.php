<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Infrastruktur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Menampilkan Dashboard Utama
     */
    public function index()
    {
        return view('admin.dashboard');
    }

    /**
     * Menampilkan Pusat Statistik dan Laporan (SINFRA)
     */
    public function statistik()
    {
        $jumlahSurveyor = User::where('role', 'surveyor')->count();
        $jumlahKabid = User::where('role', 'kabid')->count();
        $jumlahWilayah = DB::table('kecamatan')->count();
        $jumlahInfrastruktur = DB::table('infrastruktur')->whereNull('deleted_at')->count();
        $jumlahAnalisis = DB::table('analisis_ai')->whereNull('deleted_at')->count();

        return view('admin.statistik', compact(
            'jumlahSurveyor', 'jumlahKabid', 'jumlahWilayah', 'jumlahInfrastruktur', 'jumlahAnalisis'
        ));
    }

    // ==========================================================
    // MODUL MANAJEMEN PENGGUNA
    // ==========================================================

    public function users(Request $request)
    {
        $search = $request->query('search');
        $query = User::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $users = $query->get();
        return view('admin.users', compact('users'));
    }

    public function createUser()
    {
        $semuaWilayah = DB::table('kecamatan')->get();
        return view('admin.create-user', compact('semuaWilayah'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,surveyor',
            'id_kecamatan' => 'nullable|exists:kecamatan,id_kecamatan',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'id_kecamatan' => ($request->role === 'admin') ? null : $request->id_kecamatan,
        ]);

        return redirect()->route('admin.users')->with('success', 'User baru berhasil ditambahkan!');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->role === 'kabid') return redirect()->route('admin.users')->with('error', 'Akun Kabid terkunci.');
        
        $semuaWilayah = DB::table('kecamatan')->get();
        return view('admin.edit-user', compact('user', 'semuaWilayah'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,surveyor',
            'id_kecamatan' => 'nullable|exists:kecamatan,id_kecamatan',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'id_kecamatan' => ($request->role === 'admin') ? null : $request->id_kecamatan,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);
        return redirect()->route('admin.users')->with('success', 'Data user berhasil diperbarui!');
    }

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->role === 'kabid') return redirect()->route('admin.users')->with('error', 'Akun Kabid dilindungi.');
        if (auth()->id() == $user->id) return redirect()->route('admin.users')->with('error', 'Tidak bisa menghapus akun sendiri.');

        $user->delete();
        return redirect()->route('admin.users')->with('success', 'Data pengguna berhasil dihapus!');
    }


    // ==========================================================
    // MODUL MANAJEMEN WILAYAH
    // ==========================================================

    public function wilayah(Request $request)
    {
        $search = $request->query('search');
        $query = DB::table('kelurahan')
            ->join('kecamatan', 'kelurahan.id_kecamatan', '=', 'kecamatan.id_kecamatan')
            ->select('kelurahan.*', 'kecamatan.nama_kecamatan');

        if ($search) {
            $query->where('kelurahan.nama_kelurahan', 'LIKE', "%{$search}%")
                  ->orWhere('kecamatan.nama_kecamatan', 'LIKE', "%{$search}%");
        }

        $wilayah = $query->orderBy('kelurahan.id_kelurahan', 'desc')->get();
        return view('admin.wilayah', compact('wilayah'));
    }

    public function createWilayah()
    {
        $semuaKecamatan = DB::table('kecamatan')->get();
        return view('admin.create-wilayah', compact('semuaKecamatan'));
    }

    public function storeWilayah(Request $request)
    {
        $request->validate([
            'id_kecamatan' => 'required|exists:kecamatan,id_kecamatan',
            'nama_kelurahan' => 'required|string|max:100',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
        ]);

        DB::table('kelurahan')->insert([
            'id_kecamatan' => $request->id_kecamatan,
            'nama_kelurahan' => $request->nama_kelurahan,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.wilayah')->with('success', 'Data Wilayah berhasil ditambahkan!');
    }

    public function editWilayah($id)
    {
        $wilayah = DB::table('kelurahan')->where('id_kelurahan', $id)->first();
        if (!$wilayah) return redirect()->route('admin.wilayah')->with('error', 'Wilayah tidak ditemukan.');
        
        $semuaKecamatan = DB::table('kecamatan')->get();
        return view('admin.edit-wilayah', compact('wilayah', 'semuaKecamatan'));
    }

    public function updateWilayah(Request $request, $id)
    {
        $request->validate([
            'id_kecamatan' => 'required|exists:kecamatan,id_kecamatan',
            'nama_kelurahan' => 'required|string|max:100',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
        ]);

        DB::table('kelurahan')->where('id_kelurahan', $id)->update([
            'id_kecamatan' => $request->id_kecamatan,
            'nama_kelurahan' => $request->nama_kelurahan,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.wilayah')->with('success', 'Data Wilayah berhasil diperbarui!');
    }

    public function destroyWilayah($id)
    {
        DB::table('kelurahan')->where('id_kelurahan', $id)->delete();
        return redirect()->route('admin.wilayah')->with('success', 'Data Wilayah berhasil dihapus.');
    }


    // ==========================================================
    // MODUL MANAJEMEN INFRASTRUKTUR (SINFRA)
    // ==========================================================

    public function infrastruktur(Request $request)
    {
        $search = $request->query('search');
        
        $query = DB::table('infrastruktur')
            ->leftJoin('kelurahan', 'infrastruktur.id_kelurahan', '=', 'kelurahan.id_kelurahan')
            ->whereNull('infrastruktur.deleted_at');

        if ($search) {
            $query->where('nama_infrastruktur', 'LIKE', "%{$search}%");
        }

        $infrastruktur = $query->orderBy('infrastruktur.id_infrastruktur', 'desc')
                               ->select('infrastruktur.*', 'kelurahan.nama_kelurahan')
                               ->get();

        $semuaKecamatan = DB::table('kecamatan')->get();
        $semuaKelurahan = DB::table('kelurahan')->get(); 
        
        return view('admin.infrastruktur', compact('infrastruktur', 'semuaKecamatan', 'semuaKelurahan'));
    }

    public function createInfrastruktur()
    {
        $semuaKecamatan = DB::table('kecamatan')->get();
        $semuaKelurahan = DB::table('kelurahan')->get();
        
        return view('admin.create-infrastruktur', compact('semuaKecamatan', 'semuaKelurahan'));
    }

    public function storeInfrastruktur(Request $request)
    {
        $request->validate([
            'nama_infrastruktur' => 'required|string|max:255',
            'jenis_infrastruktur' => 'required|string',
            'id_kecamatan' => 'required|exists:kecamatan,id_kecamatan',
            'id_kelurahan' => 'required|exists:kelurahan,id_kelurahan',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $namaFoto = 'default.jpg';
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $namaFoto = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/infrastruktur', $namaFoto);
        }

        $jenisEnum = strtolower($request->jenis_infrastruktur);
        $allowedEnum = ['jalan', 'drainase', 'jembatan', 'pju'];
        if (!in_array($jenisEnum, $allowedEnum)) {
            $jenisEnum = 'jalan'; 
        }

        DB::table('infrastruktur')->insert([
            'id_user' => auth()->id(), 
            'id_kecamatan' => $request->id_kecamatan,
            'id_kelurahan' => $request->id_kelurahan,
            'nama_infrastruktur' => $request->nama_infrastruktur,
            'jenis_infrastruktur' => $request->jenis_infrastruktur,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'kondisi' => $request->kondisi ?? 'Baik',
            'foto_terbaru' => $namaFoto,
            'nama_objek' => $request->nama_infrastruktur, 
            'jenis' => $jenisEnum,
            'alamat' => $request->alamat ?? '-',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.infrastruktur')->with('success', 'Data Infrastruktur berhasil ditambahkan!');
    }

    public function editInfrastruktur($id)
    {
        $inf = DB::table('infrastruktur')->where('id_infrastruktur', $id)->first();
        if (!$inf) return redirect()->route('admin.infrastruktur')->with('error', 'Aset tidak ditemukan.');
        
        $semuaKecamatan = DB::table('kecamatan')->get();
        $semuaKelurahan = DB::table('kelurahan')->get();
        
        return view('admin.edit-infrastruktur', compact('inf', 'semuaKecamatan', 'semuaKelurahan'));
    }

    public function updateInfrastruktur(Request $request, $id)
    {
        $request->validate([
            'nama_infrastruktur' => 'required|string|max:255',
            'jenis_infrastruktur' => 'required|string',
            'id_kecamatan' => 'required|exists:kecamatan,id_kecamatan',
            'id_kelurahan' => 'required|exists:kelurahan,id_kelurahan',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $infraLama = DB::table('infrastruktur')->where('id_infrastruktur', $id)->first();
        $namaFoto = $infraLama->foto_terbaru;

        if ($request->hasFile('foto')) {
            if ($namaFoto != 'default.jpg') {
                Storage::delete('public/infrastruktur/' . $namaFoto);
            }
            $file = $request->file('foto');
            $namaFoto = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/infrastruktur', $namaFoto);
        }

        $jenisEnum = strtolower($request->jenis_infrastruktur);
        $allowedEnum = ['jalan', 'drainase', 'jembatan', 'pju'];
        if (!in_array($jenisEnum, $allowedEnum)) {
            $jenisEnum = 'jalan'; 
        }

        DB::table('infrastruktur')->where('id_infrastruktur', $id)->update([
            'id_kecamatan' => $request->id_kecamatan,
            'id_kelurahan' => $request->id_kelurahan,
            'nama_infrastruktur' => $request->nama_infrastruktur,
            'jenis_infrastruktur' => $request->jenis_infrastruktur,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'kondisi' => $request->kondisi,
            'foto_terbaru' => $namaFoto,
            'nama_objek' => $request->nama_infrastruktur, 
            'jenis' => $jenisEnum,
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.infrastruktur')->with('success', 'Data Infrastruktur berhasil diperbarui!');
    }

    public function destroyInfrastruktur($id)
    {
        DB::table('infrastruktur')->where('id_infrastruktur', $id)->update(['deleted_at' => now()]);
        return redirect()->route('admin.infrastruktur')->with('success', 'Data Infrastruktur berhasil dihapus.');
    }


    // ==========================================================
    // MODUL PETA SPASIAL
    // ==========================================================
    public function peta()
    {
        $semuaWilayah = DB::table('kecamatan')->get();
        $dataInfrastruktur = DB::table('infrastruktur')->whereNull('deleted_at')->get();

        return view('admin.peta', compact('semuaWilayah', 'dataInfrastruktur'));
    }
}