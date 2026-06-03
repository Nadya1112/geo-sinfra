<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

DB::statement("ALTER TABLE infrastruktur MODIFY COLUMN jenis ENUM('jalan', 'titian', 'sanitasi', 'jembatan') NOT NULL");
echo "DB Fixed!\n";
