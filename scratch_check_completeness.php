<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$totalJalan = DB::table('infrastruktur')->where('jenis', 'jalan')->count();

$jalanWithCoordsAndKec = DB::table('infrastruktur')
    ->leftJoin('kelurahan', 'infrastruktur.id_kelurahan', '=', 'kelurahan.id_kelurahan')
    ->where('infrastruktur.jenis', 'jalan')
    ->whereNotNull('infrastruktur.latitude')
    ->whereNotNull('infrastruktur.longitude')
    ->whereNotNull('kelurahan.id_kecamatan')
    ->count();

$jalanMissingKec = DB::table('infrastruktur')
    ->leftJoin('kelurahan', 'infrastruktur.id_kelurahan', '=', 'kelurahan.id_kelurahan')
    ->where('infrastruktur.jenis', 'jalan')
    ->where(function($q) {
        $q->whereNull('kelurahan.id_kecamatan')
          ->orWhereNull('infrastruktur.latitude')
          ->orWhereNull('infrastruktur.longitude');
    })
    ->count();

echo "Total Jalan: {$totalJalan}\n";
echo "Jalan Valid (Coords & Kecamatan): {$jalanWithCoordsAndKec}\n";
echo "Jalan Invalid/Missing: {$jalanMissingKec}\n";
