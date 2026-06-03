<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('infrastruktur', function (Blueprint $table) {
            $table->string('status_perbaikan')->default('Menunggu')->after('alasan_penolakan');
        });
    }

    public function down(): void
    {
        Schema::table('infrastruktur', function (Blueprint $table) {
            $table->dropColumn('status_perbaikan');
        });
    }
};
