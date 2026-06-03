<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$items = DB::table('kecamatan')->get();
foreach ($items as $i) {
    echo "ID: {$i->id_kecamatan} | Nama: {$i->nama_kecamatan}\n";
}
