<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('infrastruktur', function (Blueprint $table) {
            // Menambahkan kolom yang hilang sesuai error dan kebutuhan form
            if (!Schema::hasColumn('infrastruktur', 'nama_infrastruktur')) {
                $table->string('nama_infrastruktur')->after('id_infrastruktur');
            }
            if (!Schema::hasColumn('infrastruktur', 'jenis_infrastruktur')) {
                $table->string('jenis_infrastruktur')->after('nama_infrastruktur');
            }
            if (!Schema::hasColumn('infrastruktur', 'id_kelurahan')) {
                $table->unsignedBigInteger('id_kelurahan')->after('jenis_infrastruktur');
            }
            if (!Schema::hasColumn('infrastruktur', 'latitude')) {
                $table->string('latitude')->nullable()->after('id_kelurahan');
            }
            if (!Schema::hasColumn('infrastruktur', 'longitude')) {
                $table->string('longitude')->nullable()->after('latitude');
            }
            if (!Schema::hasColumn('infrastruktur', 'kondisi')) {
                $table->string('kondisi')->default('Baik')->after('longitude');
            }
            if (!Schema::hasColumn('infrastruktur', 'foto')) {
                $table->string('foto')->nullable()->after('kondisi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('infrastruktur', function (Blueprint $table) {
            $table->dropColumn([
                'nama_infrastruktur', 
                'jenis_infrastruktur', 
                'id_kelurahan', 
                'latitude', 
                'longitude', 
                'kondisi', 
                'foto'
            ]);
        });
    }
};