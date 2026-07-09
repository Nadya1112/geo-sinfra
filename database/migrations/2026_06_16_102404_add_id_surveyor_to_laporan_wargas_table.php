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
            // This adds the 'id_surveyor' column and correctly creates a foreign key
            // constraint referencing the 'id' column on the 'users' table.
            $table->unsignedBigInteger('id_surveyor')->nullable()->after('id_infrastruktur');
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