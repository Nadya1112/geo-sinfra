<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('citra_cnn', function (Blueprint $table) {
            $table->id('id_citra');
            $table->unsignedBigInteger('id_infrastruktur');
            $table->unsignedBigInteger('id_user')->nullable(); // Surveyor yang mengunggah
            $table->string('file_foto');
            $table->float('skor_cnn')->nullable(); // Persentase/probabilitas kerusakan
            $table->string('label_kondisi')->nullable(); // Hasil deteksi: Rusak Berat/Ringan
            $table->timestamps();
            $table->softDeletes();

            // Relasi ke tabel infrastruktur
            $table->foreign('id_infrastruktur')
                  ->references('id_infrastruktur')->on('infrastruktur')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citra_cnn');
    }
};