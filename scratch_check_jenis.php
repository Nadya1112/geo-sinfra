<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$results = DB::table('infrastruktur')
    ->select('jenis', DB::raw('count(*) as count'))
    ->groupBy('jenis')
    ->get();

foreach ($results as $r) {
    echo "Jenis: " . ($r->jenis === null ? 'NULL' : $r->jenis) . " | Count: " . $r->count . "\n";
}
