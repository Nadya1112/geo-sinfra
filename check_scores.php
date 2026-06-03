<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$res = DB::table('citra_cnn')->get();
foreach($res as $r) {
    echo "ID: {$r->id_infrastruktur} | Label: {$r->label_kondisi} | Skor: {$r->skor_cnn}\n";
}
