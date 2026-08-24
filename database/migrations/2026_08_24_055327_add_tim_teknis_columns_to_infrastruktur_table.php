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
        Schema::table('infrastruktur', function (Blueprint $table) {
            $table->text('rekomendasi_manual')->nullable()->after('kondisi');
            $table->string('pelaksana_perbaikan')->nullable()->after('status_perbaikan');
            $table->date('estimasi_selesai')->nullable()->after('pelaksana_perbaikan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('infrastruktur', function (Blueprint $table) {
            $table->dropColumn(['rekomendasi_manual', 'pelaksana_perbaikan', 'estimasi_selesai']);
        });
    }
};
