<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analisis_ai', function (Blueprint $table) {
            $table->id('id_ai');
            $table->unsignedBigInteger('id_infrastruktur');
            $table->unsignedBigInteger('id_kabid')->nullable(); // Admin/Kabid yang memvalidasi
            
            // Parameter untuk Decision Tree
            $table->string('param_kondisi')->nullable(); 
            $table->string('param_kepadatan')->nullable();
            
            // Hasil Logika Decision Tree
            $table->float('skor_dt')->nullable();
            $table->string('label_prioritas')->nullable(); // Misal: Prioritas Tinggi/Rendah
            $table->text('rekomendasi')->nullable();
            
            // Proses Validasi
            $table->text('catatan_validasi')->nullable();
            $table->string('status_validasi')->default('Pending');
            $table->timestamp('tgl_validasi')->nullable();
            
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
        Schema::dropIfExists('analisis_ai');
    }
};