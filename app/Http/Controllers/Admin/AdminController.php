<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Infrastruktur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Menampilkan Dashboard Utama (Halaman Welcome)
     */
    public function index()
    {
        return view('admin.dashboard');
    }

    /**
     * Menampilkan Pusat Statistik dan Laporan
     */
    public function statistik()
    {
        $jumlahSurveyor = User::where('role', 'surveyor')->count();
        $jumlahKabid = User::where('role', 'kabid')->count();
        $jumlahWilayah = DB::table('kecamatan')->count();
        $jumlahInfrastruktur = Infrastruktur::count();
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
        // Dropdown wilayah untuk user mengarah ke master kecamatan
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
        
        if ($user->role === 'kabid') {
            return redirect()->route('admin.users')->with('error', 'Akun Kabid terkunci.');
        }
        
        $semuaWilayah = DB::table('kecamatan')->get();
        return view('admin.edit-user', compact('user', 'semuaWilayah'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($user->role === 'kabid') return redirect()->route('admin.users');

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,surveyor',
            'id_kecamatan' => 'nullable|exists:kecamatan,id_kecamatan',
            'password' => 'nullable|string|min:8',
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

        if ($user->role === 'kabid') {
            return redirect()->route('admin.users')->with('error', 'Akun Kabid dilindungi dan tidak dapat dihapus.');
        }

        if (auth()->id() == $user->id) {
            return redirect()->route('admin.users')->with('error', 'Anda tidak dapat menghapus akun yang sedang Anda gunakan.');
        }

        $user->delete();
        return redirect()->route('admin.users')->with('success', 'Data pengguna berhasil dihapus dari sistem!');
    }


    // ==========================================================
    // MODUL MANAJEMEN WILAYAH (TERFOKUS PADA KELURAHAN)
    // ==========================================================

    public function wilayah(Request $request)
    {
        $search = $request->query('search');
        
        // Mengambil data kelurahan dan digabungkan (join) dengan master kecamatan
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
        // Mengirim daftar master kecamatan untuk dropdown di form
        $semuaKecamatan = DB::table('kecamatan')->get();
        return view('admin.create-wilayah', compact('semuaKecamatan'));
    }

    public function storeWilayah(Request $request)
    {
        $request->validate([
            'id_kecamatan' => 'required|exists:kecamatan,id_kecamatan',
            'nama_kelurahan' => 'required|string|max:100',
            'latitude' => 'required|string|max:50',
            'longitude' => 'required|string|max:50',
        ]);

        // Menyimpan data langsung ke tabel kelurahan tanpa kolom geometri
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
        // Mengambil data kelurahan berdasarkan ID kelurahan
        $wilayah = DB::table('kelurahan')->where('id_kelurahan', $id)->first();
        
        if (!$wilayah) {
            return redirect()->route('admin.wilayah')->with('error', 'Data Wilayah tidak ditemukan.');
        }

        $semuaKecamatan = DB::table('kecamatan')->get();
        return view('admin.edit-wilayah', compact('wilayah', 'semuaKecamatan'));
    }

    public function updateWilayah(Request $request, $id)
    {
        $request->validate([
            'id_kecamatan' => 'required|exists:kecamatan,id_kecamatan',
            'nama_kelurahan' => 'required|string|max:100',
            'latitude' => 'required|string|max:50',
            'longitude' => 'required|string|max:50',
        ]);

        DB::table('kelurahan')
            ->where('id_kelurahan', $id)
            ->update([
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
        // Langsung menghapus kelurahan (tidak mempengaruhi kecamatan master)
        DB::table('kelurahan')->where('id_kelurahan', $id)->delete();

        return redirect()->route('admin.wilayah')->with('success', 'Data Wilayah berhasil dihapus.');
    }


    // ==========================================================
    // MODUL MANAJEMEN INFRASTRUKTUR
    // ==========================================================
    public function infrastruktur(Request $request)
    {
        $search = $request->query('search');
        
        // Memanggil data infrastruktur
        $query = DB::table('infrastruktur')->whereNull('deleted_at');

        if ($search) {
            $query->where('nama_infrastruktur', 'LIKE', "%{$search}%");
        }

        $infrastruktur = $query->orderBy('id_infrastruktur', 'desc')->get();
        
        return view('admin.infrastruktur', compact('infrastruktur'));
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