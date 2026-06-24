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
        Schema::table('analisis_ai', function (Blueprint $table) {
            if (!Schema::hasColumn('analisis_ai', 'id_kabid')) {
                $table->unsignedBigInteger('id_kabid')->nullable();
            }
            if (!Schema::hasColumn('analisis_ai', 'param_kondisi')) {
                $table->text('param_kondisi')->nullable();
            }
            if (!Schema::hasColumn('analisis_ai', 'param_kepadatan')) {
                $table->string('param_kepadatan')->nullable();
            }
            if (!Schema::hasColumn('analisis_ai', 'skor_dt')) {
                $table->float('skor_dt')->nullable();
            }
            if (!Schema::hasColumn('analisis_ai', 'label_prioritas')) {
                $table->string('label_prioritas')->nullable();
            }
            if (!Schema::hasColumn('analisis_ai', 'rekomendasi')) {
                $table->text('rekomendasi')->nullable();
            }
            if (!Schema::hasColumn('analisis_ai', 'catatan_validasi')) {
                $table->text('catatan_validasi')->nullable();
            }
            if (!Schema::hasColumn('analisis_ai', 'status_validasi')) {
                $table->string('status_validasi')->default('Pending');
            }
            if (!Schema::hasColumn('analisis_ai', 'tgl_validasi')) {
                $table->timestamp('tgl_validasi')->nullable();
            }
            if (!Schema::hasColumn('analisis_ai', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No down migration needed for safely adding missing columns
    }
};
