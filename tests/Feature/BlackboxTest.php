<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class BlackboxTest extends TestCase
{
    /**
     * Skenario 1: Halaman Login bisa diakses
     */
    public function test_halaman_login_bisa_diakses()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Login');
    }

    /**
     * Skenario 2: Login Admin Berhasil dan diarahkan ke Dashboard
     */
    public function test_login_admin_berhasil_dan_masuk_dashboard()
    {
        // Buat user dummy admin
        $admin = User::factory()->create([
            'email' => 'testadmin@disperkim.go.id',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);

        $response = $this->post('/login', [
            'login' => 'testadmin@disperkim.go.id',
            'password' => 'password123',
        ]);

        // Cek redirect ke halaman dashboard admin
        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($admin);
        
        // Hapus dummy user setelah tes
        $admin->delete();
    }

    /**
     * Skenario 3: Login gagal jika password salah
     */
    public function test_login_gagal_password_salah()
    {
        $admin = User::factory()->create([
            'email' => 'testadmin2@disperkim.go.id',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);

        $response = $this->post('/login', [
            'login' => 'testadmin2@disperkim.go.id',
            'password' => 'passwordsalah',
        ]);

        // Harus kembali ke halaman login membawa error
        $response->assertSessionHasErrors(['login']);
        $this->assertGuest();
        
        $admin->delete();
    }

    /**
     * Skenario 4: Login Surveyor diarahkan ke Dashboard Surveyor
     */
    public function test_login_surveyor_berhasil()
    {
        $surveyor = User::factory()->create([
            'email' => 'surveyor_test@disperkim.go.id',
            'password' => Hash::make('password123'),
            'role' => 'surveyor'
        ]);

        $response = $this->post('/login', [
            'login' => 'surveyor_test@disperkim.go.id',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/surveyor/dashboard');
        $this->assertAuthenticatedAs($surveyor);
        
        $surveyor->delete();
    }

    /**
     * Skenario 5: Halaman Admin tidak bisa diakses tanpa Login
     */
    public function test_halaman_admin_terproteksi()
    {
        // User belum login mencoba akses halaman admin
        $response = $this->get('/admin/dashboard');
        
        // Harus diarahkan kembali ke halaman login
        $response->assertRedirect('/login');
    }
}
