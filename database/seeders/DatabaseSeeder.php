<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan emailnya sudah admin@disperkim.go.id
        User::create([
            'name'     => 'Administrator GEO-SINFRA',
            'email'    => 'admin@disperkim.go.id',
            'password' => Hash::make('admin123'), // Sandi tetap admin123
            'role'     => 'admin',
        ]);

        // Akun tambahan untuk tes
        User::create([
            'name'     => 'Surveyor Disperkim',
            'email'    => 'surveyor@disperkim.go.id',
            'password' => Hash::make('surveyor123'),
            'role'     => 'surveyor',
        ]);
    }
}