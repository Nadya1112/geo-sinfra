<?php
$lines = file(__DIR__ . '/../resources/views/landing.blade.php');
file_put_contents(__DIR__ . '/landing_js_part2.txt', implode("", array_slice($lines, 1000, 700)));
