<?php
$file = 'resources/views/surveyor/map.blade.php';
$content = file_get_contents($file);

// Ensure the floating UI cards always use white/opacity instead of solid white, since they are on a dark navy background.
$content = str_replace('bg-white dark:bg-[#1e1b4b]/5', 'bg-white/5', $content);
$content = str_replace('hover:bg-white dark:bg-[#1e1b4b]/10', 'hover:bg-white/10', $content);
$content = str_replace('bg-white dark:bg-[#1e1b4b]/10', 'bg-white/10', $content);
$content = str_replace('hover:bg-white dark:bg-[#1e1b4b]/20', 'hover:bg-white/20', $content);
$content = str_replace('bg-white dark:bg-[#1e1b4b]', 'bg-white dark:bg-[#1e1b4b]', $content); // header, ignore this one by not replacing with anything different

file_put_contents($file, $content);
echo "Fixed map controls visibility in light mode.";
