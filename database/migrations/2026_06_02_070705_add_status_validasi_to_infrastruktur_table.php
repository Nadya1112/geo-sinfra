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
            $table->enum('status_validasi', ['Pending', 'Validated', 'Rejected'])->default('Pending')->after('status_verifikasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('infrastruktur', function (Blueprint $table) {
            $table->dropColumn('status_validasi');
        });
    }
};
