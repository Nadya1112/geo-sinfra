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
            $table->string('jenis_ai')->nullable()->after('label_ai')->comment('Tebakan AI: jalan, jembatan, titian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_warga', function (Blueprint $table) {
            $table->dropColumn('jenis_ai');
        });
    }
};
