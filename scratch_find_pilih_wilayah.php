<?php
$viewsDir = 'c:/laragon1/laragon/www/geo-sinfra/resources/views';
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));

foreach ($files as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        if (stripos($content, 'PILIH WILAYAH') !== false || stripos($content, 'Pilih Wilayah') !== false) {
            echo "Match found in: " . $file->getPathname() . "\n";
        }
    }
}
