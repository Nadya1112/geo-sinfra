<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$items = DB::table('infrastruktur')
    ->leftJoin('kelurahan', 'infrastruktur.id_kelurahan', '=', 'kelurahan.id_kelurahan')
    ->leftJoin('kecamatan', 'kelurahan.id_kecamatan', '=', 'kecamatan.id_kecamatan')
    ->leftJoin('analisis_ai', 'infrastruktur.id_infrastruktur', '=', 'analisis_ai.id_infrastruktur')
    ->select(
        'infrastruktur.*', 
        'kelurahan.id_kecamatan as id_kecamatan_from_kel',
        'kecamatan.nama_kecamatan',
        'analisis_ai.label_prioritas',
        'analisis_ai.skor_dt'
    )
    ->where('infrastruktur.jenis', 'jalan')
    ->limit(5)
    ->get()
    ->map(function($item) {
        $item->id_kecamatan = $item->id_kecamatan ?? $item->id_kecamatan_from_kel;
        return $item;
    });

foreach ($items as $i) {
    echo "ID: {$i->id_infrastruktur} | id_kecamatan: " . ($i->id_kecamatan === null ? 'NULL' : $i->id_kecamatan) . " | id_kecamatan_from_kel: " . $i->id_kecamatan_from_kel . "\n";
}
