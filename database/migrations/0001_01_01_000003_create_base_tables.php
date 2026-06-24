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
        Schema::create('kecamatan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kecamatan')->nullable();
            $table->json('geometri')->nullable();
            $table->string('warna')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('kelurahan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_kecamatan')->nullable();
            $table->string('nama_kelurahan')->nullable();
            $table->string('latitude')->nullable(); 
            $table->string('longitude')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('wilayah', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_kelurahan')->nullable();
            $table->json('geometri')->nullable();
            $table->string('warna')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('infrastruktur', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user')->nullable();
            $table->unsignedBigInteger('id_kelurahan')->nullable();
            $table->string('nama_objek')->nullable();
            $table->string('foto_terbaru')->nullable();
            $table->string('jenis')->nullable();
            $table->string('material_eksisting')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('kondisi')->nullable();
            $table->double('panjang')->nullable();
            $table->double('lebar')->nullable();
            $table->boolean('has_drainase')->default(false);
            $table->boolean('has_gorong_gorong')->default(false);
            $table->text('rencana_perbaikan')->nullable();
            $table->date('tgl_survey')->nullable();

            // Needed by migrations
            $table->string('jenis_infrastruktur')->nullable(); 
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infrastruktur');
        Schema::dropIfExists('wilayah');
        Schema::dropIfExists('kelurahan');
        Schema::dropIfExists('kecamatan');
    }
};
