<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$sanitasi = DB::table('infrastruktur')->where('jenis', 'sanitasi')->get();
$duplicates = [];
$counts = [];

foreach ($sanitasi as $s) {
    $name = strtolower(trim($s->nama_objek));
    if (!isset($counts[$name])) {
        $counts[$name] = [];
    }
    $counts[$name][] = $s->id_infrastruktur;
}

$to_delete = [];
foreach ($counts as $name => $ids) {
    if (count($ids) > 1) {
        echo "Duplicate found: '$name' (" . count($ids) . " records)\n";
        // Keep the first one, delete the rest
        for ($i = 1; $i < count($ids); $i++) {
            $to_delete[] = $ids[$i];
        }
    }
}

echo "Total duplicates to delete: " . count($to_delete) . "\n";

if (count($to_delete) > 0) {
    DB::table('infrastruktur')->whereIn('id_infrastruktur', $to_delete)->delete();
    echo "Deleted " . count($to_delete) . " duplicate records.\n";
}

$final_count = DB::table('infrastruktur')->where('jenis', 'sanitasi')->count();
echo "Final Sanitasi count in DB: " . $final_count . "\n";
