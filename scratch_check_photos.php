<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Infrastruktur;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

$items = Infrastruktur::limit(20)->get();
foreach ($items as $i) {
    $path = $i->foto_terbaru;
    $fullPath = storage_path('app/public/' . $path);
    $exists = file_exists($fullPath) ? 'EXISTS' : 'MISSING';
    echo "ID: {$i->id_infrastruktur} | Path: {$path} | Status: {$exists}\n";
}
