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
        $driver = DB::connection()->getDriverName();
        if ($driver !== 'sqlite') {
            DB::statement("ALTER TABLE laporan_warga MODIFY COLUMN status ENUM('Menunggu', 'Menunggu Review', 'Ditinjau', 'Diproses', 'Selesai', 'Ditolak') DEFAULT 'Menunggu'");
        }
        
        DB::table('laporan_warga')->where('status', 'Menunggu Review')->update(['status' => 'Menunggu']);
        
        if ($driver !== 'sqlite') {
            DB::statement("ALTER TABLE laporan_warga MODIFY COLUMN status ENUM('Menunggu', 'Ditinjau', 'Diproses', 'Selesai', 'Ditolak') DEFAULT 'Menunggu'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::connection()->getDriverName();
        if ($driver !== 'sqlite') {
            DB::statement("ALTER TABLE laporan_warga MODIFY COLUMN status ENUM('Menunggu', 'Menunggu Review', 'Ditinjau', 'Diproses', 'Selesai', 'Ditolak') DEFAULT 'Menunggu Review'");
        }
        
        DB::table('laporan_warga')->where('status', 'Menunggu')->update(['status' => 'Menunggu Review']);
        
        if ($driver !== 'sqlite') {
            DB::statement("ALTER TABLE laporan_warga MODIFY COLUMN status ENUM('Menunggu Review', 'Diproses', 'Selesai') DEFAULT 'Menunggu Review'");
        }
    }
};
