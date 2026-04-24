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
     * Menampilkan Dashboard Utama (Statistik)
     */
    public function index()
    {
        $jumlahSurveyor = User::where('role', 'surveyor')->count();
        $jumlahKabid = User::where('role', 'kabid')->count();
        $jumlahWilayah = DB::table('kecamatan')->count();
        $jumlahInfrastruktur = Infrastruktur::count();
        $jumlahAnalisis = DB::table('analisis_ai')->whereNull('deleted_at')->count();

        return view('admin.dashboard', compact(
            'jumlahSurveyor', 'jumlahKabid', 'jumlahWilayah', 'jumlahInfrastruktur', 'jumlahAnalisis'
        ));
    }

    /**
     * Menampilkan Daftar Manajemen Pengguna + Fitur Cari
     */
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

    /**
     * Menampilkan Form Tambah User
     */
    public function createUser()
    {
        $semuaWilayah = DB::table('kecamatan')->whereNull('deleted_at')->get();
        return view('admin.create-user', compact('semuaWilayah'));
    }

    /**
     * Menyimpan User Baru ke Database
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,surveyor',
            // PERBAIKAN: id_kecamatan sekarang opsional (nullable) untuk semua role
            'id_kecamatan' => 'nullable|exists:kecamatan,id_kecamatan',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            // Logika: Tetap paksa null jika role admin, selain itu ikuti input (bisa null)
            'id_kecamatan' => ($request->role === 'admin') ? null : $request->id_kecamatan,
        ]);

        return redirect()->route('admin.users')->with('success', 'User baru berhasil ditambahkan!');
    }

    /**
     * Menampilkan Form Edit User
     */
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->role === 'kabid') {
            return redirect()->route('admin.users')->with('error', 'Akun Kabid terkunci.');
        }
        
        $semuaWilayah = DB::table('kecamatan')->whereNull('deleted_at')->get();
        return view('admin.edit-user', compact('user', 'semuaWilayah'));
    }

    /**
     * Menyimpan Perubahan User
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($user->role === 'kabid') return redirect()->route('admin.users');

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,surveyor',
            // PERBAIKAN: id_kecamatan sekarang opsional (nullable) untuk semua role
            'id_kecamatan' => 'nullable|exists:kecamatan,id_kecamatan',
            'password' => 'nullable|string|min:8',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            // Logika: Tetap paksa null jika role admin, selain itu ikuti input (bisa null)
            'id_kecamatan' => ($request->role === 'admin') ? null : $request->id_kecamatan,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);
        return redirect()->route('admin.users')->with('success', 'Data user berhasil diperbarui!');
    }

    /**
     * Menampilkan Halaman Peta Spasial
     */
    public function peta()
    {
        $semuaWilayah = DB::table('kecamatan')->whereNull('deleted_at')->get();
        $dataInfrastruktur = DB::table('infrastruktur')->whereNull('deleted_at')->get();

        return view('admin.peta', compact('semuaWilayah', 'dataInfrastruktur'));
    }
}