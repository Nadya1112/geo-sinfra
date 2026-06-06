<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AnalisisAi;
use Illuminate\Support\Facades\DB;

class RecalculateAiScores extends Command
{
    protected $signature = 'ai:recalculate';
    protected $description = 'Re-kalkulasi semua skor AI (Hybrid CNN + Decision Tree) untuk seluruh data infrastruktur';

    public function handle()
    {
        $this->info('🔄 Memulai re-kalkulasi skor AI untuk seluruh data...');
        $this->newLine();

        // Ambil semua infrastruktur yang sudah punya data analisis atau CNN
        $infraIds = DB::table('infrastruktur')
            ->pluck('id_infrastruktur');

        $total = $infraIds->count();
        $updated = 0;
        $skipped = 0;

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        foreach ($infraIds as $id) {
            try {
                // Cek apakah ada data CNN untuk infrastruktur ini
                $hasCnn = DB::table('citra_cnn')->where('id_infrastruktur', $id)->exists();
                $hasAnalisis = DB::table('analisis_ai')->where('id_infrastruktur', $id)->exists();

                if ($hasCnn || $hasAnalisis) {
                    // Ambil label lama untuk perbandingan
                    $oldAnalisis = DB::table('analisis_ai')->where('id_infrastruktur', $id)->first();
                    $oldLabel = $oldAnalisis ? $oldAnalisis->label_prioritas : '-';
                    $oldSkor = $oldAnalisis ? $oldAnalisis->skor_dt : 0;

                    // Re-kalkulasi
                    AnalisisAi::calculateHybrid($id);

                    // Ambil label baru
                    $newAnalisis = DB::table('analisis_ai')->where('id_infrastruktur', $id)->first();
                    $newLabel = $newAnalisis ? $newAnalisis->label_prioritas : '-';
                    $newSkor = $newAnalisis ? $newAnalisis->skor_dt : 0;

                    // Log jika ada perubahan
                    if ($oldLabel !== $newLabel) {
                        $this->newLine();
                        $this->warn("  ⚡ ID #{$id}: [{$oldLabel} ({$oldSkor})] → [{$newLabel} ({$newSkor})]");
                    }

                    $updated++;
                } else {
                    $skipped++;
                }
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("  ❌ ID #{$id}: " . $e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("✅ Selesai!");
        $this->table(
            ['Keterangan', 'Jumlah'],
            [
                ['Total Infrastruktur', $total],
                ['Berhasil Re-kalkulasi', $updated],
                ['Dilewati (Tidak ada data AI)', $skipped],
            ]
        );
    }
}
