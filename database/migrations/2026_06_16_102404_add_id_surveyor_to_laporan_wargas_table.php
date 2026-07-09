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
        if (!Schema::hasColumn('laporan_warga', 'id_surveyor')) {
            Schema::table('laporan_warga', function (Blueprint $table) {
                $table->unsignedBigInteger('id_surveyor')->nullable()->after('id_infrastruktur');
            });
        }

        Schema::table('laporan_warga', function (Blueprint $table) {
            $table->foreign('id_surveyor')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_warga', function (Blueprint $table) {
            $table->dropForeign(['id_surveyor']);
            $table->dropColumn('id_surveyor');
        });
    }
};