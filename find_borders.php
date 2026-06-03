<?php

$content = file_get_contents('c:\laragon1\laragon\www\geo-sinfra\resources\views\landing.blade.php');
$lines = explode("\n", $content);

echo "=== MATCHES FOR BORDER OR OUTLINE ===\n";
foreach ($lines as $num => $line) {
    if (stripos($line, 'border') !== false || stripos($line, 'outline') !== false || stripos($line, 'black') !== false) {
        echo "Line " . ($num + 1) . ": " . trim($line) . "\n";
    }
}
