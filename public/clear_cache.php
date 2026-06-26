<?php
// Script untuk membersihkan cache Laravel dari browser
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->call('view:clear');
$kernel->call('cache:clear');
$kernel->call('config:clear');
$kernel->call('route:clear');

echo "<h1>Semua Cache Berhasil Dihapus!</h1>";
echo "<p>Silakan kembali ke halaman website Anda dan tekan Ctrl + F5.</p>";
