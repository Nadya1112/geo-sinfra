<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Cek dulu, kalau belum ada kolomnya, baru ditambahkan
            if (!Schema::hasColumn('users', 'id_kecamatan')) {
                // Menambahkan kolom setelah role, tipe datanya harus sama dengan kolom id_kecamatan di tabel kecamatan
                $table->string('id_kecamatan', 10)->nullable()->after('role');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'id_kecamatan')) {
                $table->dropColumn('id_kecamatan');
            }
        });
    }
};