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
        Schema::table('laporan_warga', function (Blueprint $table) {
            $table->unsignedBigInteger('id_infrastruktur')->nullable()->after('id');
            
            // Opsional: tambahkan foreign key jika infrastruktur menggunakan unsignedBigInteger untuk id_infrastruktur. 
            // Tapi karena id_infrastruktur di tabel infrastruktur mungkin tidak unsignedBigInteger (di migrasi sebelumnya),
            // kita jadikan kolom biasa saja untuk mengamankan.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_warga', function (Blueprint $table) {
            $table->dropColumn('id_infrastruktur');
        });
    }
};
