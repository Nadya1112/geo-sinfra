<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\AnalisisAi;

$infras = DB::table('infrastruktur')->get();
foreach($infras as $infra) {
    AnalisisAi::calculateHybrid($infra->id_infrastruktur);
}

echo "Berhasil update skor hybrid " . count($infras) . " data!\n";
