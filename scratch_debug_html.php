<?php
$content = file_get_contents('c:/laragon1/laragon/www/geo-sinfra/resources/views/landing.blade.php');
$start = strpos($content, '<div id="kategori-menu"');
if ($start !== false) {
    $end = strpos($content, '</div>', $start);
    // Print about 1000 characters from start
    echo substr($content, $start, 1000) . "\n";
} else {
    echo "kategori-menu not found!\n";
}
