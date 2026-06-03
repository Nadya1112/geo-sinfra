<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$item = DB::table('infrastruktur')
    ->leftJoin('kelurahan', 'infrastruktur.id_kelurahan', '=', 'kelurahan.id_kelurahan')
    ->leftJoin('kecamatan', 'kelurahan.id_kecamatan', '=', 'kecamatan.id_kecamatan')
    ->select(
        'infrastruktur.*',
        'kelurahan.id_kecamatan as id_kecamatan_from_kel',
        'kecamatan.nama_kecamatan'
    )
    ->first();

print_r($item);
