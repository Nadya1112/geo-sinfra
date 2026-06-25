<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$columns = Illuminate\Support\Facades\Schema::getColumnListing('users');
echo "Columns in users table: " . implode(', ', $columns) . "\n";

$infraColumns = Illuminate\Support\Facades\Schema::getColumnListing('infrastruktur');
echo "Columns in infrastruktur table: " . implode(', ', $infraColumns) . "\n";
