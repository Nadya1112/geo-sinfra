<?php
$viewsDir = 'c:/laragon1/laragon/www/geo-sinfra/resources/views';
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));

foreach ($files as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        $matches = [];
        preg_match_all('/(titian|jembatan|sanitasi|jalan)/i', $content, $matches);
        if (!empty($matches[0])) {
            $counts = array_count_values(array_map('strtolower', $matches[0]));
            echo "File: " . basename($file->getPathname()) . "\n";
            foreach ($counts as $word => $count) {
                echo "  - {$word}: {$count}\n";
            }
        }
    }
}
