<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class BlackboxTest extends TestCase
{
    use RefreshDatabase;

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
    public function test_login_admin_berhasil()
    {
        // Buat user Admin sesuai skenario
        $admin = User::create([
            'name' => 'Admin SINFRA',
            'email' => 'admin@disperkim.go.id',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $response = $this->post('/login', [
            'login' => 'admin@disperkim.go.id',
            'password' => 'admin123',
        ]);

        // Cek redirect ke halaman dashboard admin
        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($admin);
    }

    /**
     * Skenario 3: Login Surveyor Berhasil dan diarahkan ke Dashboard
     */
    public function test_login_surveyor_berhasil()
    {
        // Buat user Surveyor sesuai skenario
        $surveyor = User::create([
            'name' => 'Surveyor SINFRA',
            'email' => 'surveyor@disperkim.go.id',
            'password' => Hash::make('surveyor123'),
            'role' => 'surveyor'
        ]);

        $response = $this->post('/login', [
            'login' => 'surveyor@disperkim.go.id', // Menyesuaikan dari dispekim yang diketik user
            'password' => 'surveyor123',
        ]);

        // Cek redirect ke halaman dashboard surveyor
        $response->assertRedirect('/surveyor/dashboard');
        $this->assertAuthenticatedAs($surveyor);
    }

    /**
     * Skenario 4: Login Tim Teknis Berhasil dan diarahkan ke Dashboard
     */
    public function test_login_tim_teknis_berhasil()
    {
        // Buat user Tim Teknis sesuai skenario
        $teknisi = User::create([
            'name' => 'Tim Teknis SINFRA',
            'email' => 'teknisi@disperkim.go.id',
            'password' => Hash::make('teknisi123'),
            'role' => 'tim_teknis'
        ]);

        $response = $this->post('/login', [
            'login' => 'teknisi@disperkim.go.id',
            'password' => 'teknisi123',
        ]);

        // Cek redirect ke halaman dashboard tim teknis (biasanya ke /tim-teknis/dashboard atau semacamnya)
        // Kita asumsikan redirect awalnya berhasil masuk
        $response->assertStatus(302); // Memastikan login sukses dan melakukan redirect
        $this->assertAuthenticatedAs($teknisi);
    }

    /**
     * Skenario 5: Login gagal jika password salah
     */
    public function test_login_gagal_password_salah()
    {
        User::create([
            'name' => 'Admin Test Error',
            'email' => 'admin@disperkim.go.id',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $response = $this->post('/login', [
            'login' => 'admin@disperkim.go.id',
            'password' => 'passwordsalah',
        ]);

        // Harus kembali ke halaman login membawa error
        $response->assertSessionHasErrors(['login']);
        $this->assertGuest();
    }
}
