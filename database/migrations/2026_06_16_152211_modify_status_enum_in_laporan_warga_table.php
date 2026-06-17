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
        DB::statement("ALTER TABLE laporan_warga MODIFY COLUMN status ENUM('Menunggu', 'Menunggu Review', 'Ditinjau', 'Diproses', 'Selesai', 'Ditolak') DEFAULT 'Menunggu'");
        DB::statement("UPDATE laporan_warga SET status = 'Menunggu' WHERE status = 'Menunggu Review'");
        DB::statement("ALTER TABLE laporan_warga MODIFY COLUMN status ENUM('Menunggu', 'Ditinjau', 'Diproses', 'Selesai', 'Ditolak') DEFAULT 'Menunggu'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE laporan_warga MODIFY COLUMN status ENUM('Menunggu', 'Menunggu Review', 'Ditinjau', 'Diproses', 'Selesai', 'Ditolak') DEFAULT 'Menunggu Review'");
        DB::statement("UPDATE laporan_warga SET status = 'Menunggu Review' WHERE status = 'Menunggu'");
        DB::statement("ALTER TABLE laporan_warga MODIFY COLUMN status ENUM('Menunggu Review', 'Diproses', 'Selesai') DEFAULT 'Menunggu Review'");
    }
};
