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
            $table->uuid('uuid')->nullable()->after('id_infrastruktur')->unique();
        });

        Schema::table('laporan_warga', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->after('id')->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            //
        });
    }
};
