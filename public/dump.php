<?php
$lines = file(__DIR__ . '/../resources/views/landing.blade.php');
file_put_contents(__DIR__ . '/landing_part2.txt', implode("", array_slice($lines, 750, 750)));
file_put_contents(__DIR__ . '/landing_part3.txt', implode("", array_slice($lines, 1500, 750)));
