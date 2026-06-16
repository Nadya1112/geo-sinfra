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
        Schema::create('laporan_warga', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pelapor');
            $table->string('no_hp')->nullable();
            $table->text('deskripsi');
            $table->string('foto');
            $table->double('latitude', 15, 8);
            $table->double('longitude', 15, 8);
            $table->enum('status', ['Menunggu Review', 'Diproses', 'Selesai'])->default('Menunggu Review');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_warga');
    }
};
