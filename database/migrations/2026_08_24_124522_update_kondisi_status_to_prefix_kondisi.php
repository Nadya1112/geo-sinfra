<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('analisis_ai')->where('label_prioritas', 'Baik')->update(['label_prioritas' => 'Kondisi Baik']);
        DB::table('analisis_ai')->where('label_prioritas', 'Rusak Ringan')->update(['label_prioritas' => 'Kondisi Rusak Ringan']);
        DB::table('analisis_ai')->where('label_prioritas', 'Rusak Sedang')->update(['label_prioritas' => 'Kondisi Rusak Sedang']);
        DB::table('analisis_ai')->where('label_prioritas', 'Rusak Berat')->update(['label_prioritas' => 'Kondisi Rusak Berat']);

        if (Schema::hasTable('citra_cnn')) {
            DB::table('citra_cnn')->where('label_kondisi', 'Baik')->update(['label_kondisi' => 'Kondisi Baik']);
            DB::table('citra_cnn')->where('label_kondisi', 'Rusak Ringan')->update(['label_kondisi' => 'Kondisi Rusak Ringan']);
            DB::table('citra_cnn')->where('label_kondisi', 'Rusak Sedang')->update(['label_kondisi' => 'Kondisi Rusak Sedang']);
            DB::table('citra_cnn')->where('label_kondisi', 'Rusak Berat')->update(['label_kondisi' => 'Kondisi Rusak Berat']);
        }
        
        if (Schema::hasTable('laporan_warga')) {
            DB::table('laporan_warga')->where('label_ai', 'Baik')->update(['label_ai' => 'Kondisi Baik']);
            DB::table('laporan_warga')->where('label_ai', 'Rusak Ringan')->update(['label_ai' => 'Kondisi Rusak Ringan']);
            DB::table('laporan_warga')->where('label_ai', 'Rusak Sedang')->update(['label_ai' => 'Kondisi Rusak Sedang']);
            DB::table('laporan_warga')->where('label_ai', 'Rusak Berat')->update(['label_ai' => 'Kondisi Rusak Berat']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('analisis_ai')->where('label_prioritas', 'Kondisi Baik')->update(['label_prioritas' => 'Baik']);
        DB::table('analisis_ai')->where('label_prioritas', 'Kondisi Rusak Ringan')->update(['label_prioritas' => 'Rusak Ringan']);
        DB::table('analisis_ai')->where('label_prioritas', 'Kondisi Rusak Sedang')->update(['label_prioritas' => 'Rusak Sedang']);
        DB::table('analisis_ai')->where('label_prioritas', 'Kondisi Rusak Berat')->update(['label_prioritas' => 'Rusak Berat']);
    }
};
