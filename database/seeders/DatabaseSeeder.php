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
        User::updateOrCreate(['email' => 'admin@disperkim.go.id'], [
            'name'     => 'Administrator GEO-SINFRA',
            'password' => Hash::make('admin123'),
            'role'     => 'admin'
        ]);

                // Akun tambahan untuk tes
        User::updateOrCreate(['email' => 'surveyor@disperkim.go.id'], [
            'name'     => 'Surveyor Disperkim',
            'password' => Hash::make('surveyor123'),
            'role'     => 'surveyor'
        ]);
        
        // Akun Tim Teknis
        User::updateOrCreate(['email' => 'timteknis@disperkim.go.id'], [
            'name'     => 'Tim Teknis Disperkim',
            'password' => Hash::make('timteknis123'),
            'role'     => 'tim_teknis'
        ]);
    }
}