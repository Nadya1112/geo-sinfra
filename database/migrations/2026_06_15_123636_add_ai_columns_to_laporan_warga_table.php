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
            $table->decimal('skor_ai', 5, 2)->nullable()->after('foto');
            $table->string('label_ai')->nullable()->after('skor_ai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_warga', function (Blueprint $table) {
            $table->dropColumn(['skor_ai', 'label_ai']);
        });
    }
};
