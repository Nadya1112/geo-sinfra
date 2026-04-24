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
        $semuaWilayah = DB::table('kecamatan')->whereNull('deleted_at')->get();
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
        
        $semuaWilayah = DB::table('kecamatan')->whereNull('deleted_at')->get();
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
    // MODUL MANAJEMEN WILAYAH (KECAMATAN)
    // ==========================================================

    public function wilayah(Request $request)
    {
        $search = $request->query('search');
        $query = DB::table('kecamatan')->whereNull('deleted_at');

        if ($search) {
            $query->where('nama_kecamatan', 'LIKE', "%{$search}%");
        }

        $wilayah = $query->orderBy('id_kecamatan')->get();
        return view('admin.wilayah', compact('wilayah'));
    }

    public function createWilayah()
    {
        return view('admin.create-wilayah');
    }

    public function storeWilayah(Request $request)
    {
        $request->validate([
            'id_kecamatan' => 'required|string|max:10|unique:kecamatan,id_kecamatan',
            'nama_kecamatan' => 'required|string|max:100',
            'warna' => 'nullable|string|max:20',
            'geometri' => 'nullable|json',
        ]);

        DB::table('kecamatan')->insert([
            'id_kecamatan' => $request->id_kecamatan,
            'nama_kecamatan' => $request->nama_kecamatan,
            'warna' => $request->warna ?? '#3b82f6',
            'geometri' => $request->geometri,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.wilayah')->with('success', 'Data Kecamatan berhasil ditambahkan!');
    }

    public function editWilayah($id)
    {
        $wilayah = DB::table('kecamatan')->where('id_kecamatan', $id)->whereNull('deleted_at')->first();
        
        if (!$wilayah) {
            return redirect()->route('admin.wilayah')->with('error', 'Data Kecamatan tidak ditemukan.');
        }

        return view('admin.edit-wilayah', compact('wilayah'));
    }

    public function updateWilayah(Request $request, $id)
    {
        $request->validate([
            'nama_kecamatan' => 'required|string|max:100',
            'warna' => 'nullable|string|max:20',
            'geometri' => 'nullable|json',
        ]);

        DB::table('kecamatan')
            ->where('id_kecamatan', $id)
            ->update([
                'nama_kecamatan' => $request->nama_kecamatan,
                'warna' => $request->warna ?? '#3b82f6',
                'geometri' => $request->geometri,
                'updated_at' => now(),
            ]);

        return redirect()->route('admin.wilayah')->with('success', 'Data Kecamatan berhasil diperbarui!');
    }

    public function destroyWilayah($id)
    {
        // Pengecekan: Jangan hapus jika masih ada user yang bertugas di wilayah ini
        $userTerkait = User::where('id_kecamatan', $id)->count();
        if ($userTerkait > 0) {
            return redirect()->route('admin.wilayah')->with('error', "Gagal menghapus! Wilayah ini sedang ditugaskan kepada {$userTerkait} Surveyor.");
        }

        DB::table('kecamatan')
            ->where('id_kecamatan', $id)
            ->update(['deleted_at' => now()]);

        return redirect()->route('admin.wilayah')->with('success', 'Data Kecamatan berhasil dihapus.');
    }


    // ==========================================================
    // MODUL PETA SPASIAL
    // ==========================================================
    public function peta()
    {
        $semuaWilayah = DB::table('kecamatan')->whereNull('deleted_at')->get();
        $dataInfrastruktur = DB::table('infrastruktur')->whereNull('deleted_at')->get();

        return view('admin.peta', compact('semuaWilayah', 'dataInfrastruktur'));
    }
}