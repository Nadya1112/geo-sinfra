<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$items = DB::table('infrastruktur')
    ->where('jenis', 'jalan')
    ->limit(10)
    ->get();

foreach ($items as $i) {
    echo "ID: {$i->id_infrastruktur} | Latitude: {$i->latitude} | Longitude: {$i->longitude}\n";
}
