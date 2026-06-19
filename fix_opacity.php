<?php
$views = glob('resources/views/tim_teknis/*.blade.php');

foreach ($views as $file) {
    $content = file_get_contents($file);
    
    // Fix: hover:bg-white dark:bg-[#1e1b4b]/10  ->  hover:bg-white/10 dark:hover:bg-[#1e1b4b]/10
    // Fix: bg-white dark:bg-[#1e1b4b]/20  ->  bg-white/20 dark:bg-[#1e1b4b]/20
    $content = preg_replace_callback(
        '/(hover:|group-hover:)?bg-white dark:bg-\[\#1e1b4b\]\/([0-9]+)/',
        function ($matches) {
            $prefix = $matches[1]; // 'hover:', 'group-hover:', or ''
            $opacity = $matches[2]; // '10', '20', etc
            
            $darkPrefix = $prefix ? 'dark:' . $prefix : 'dark:';
            return "{$prefix}bg-white/{$opacity} {$darkPrefix}bg-[#1e1b4b]/{$opacity}";
        },
        $content
    );

    file_put_contents($file, $content);
    echo "Fixed: $file\n";
}
echo "Done.\n";
