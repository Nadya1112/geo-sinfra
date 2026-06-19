<?php
$dir = new RecursiveDirectoryIterator('resources/views/surveyor');
$iter = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($iter, '/^.+\.blade\.php$/i', RecursiveRegexIterator::GET_MATCH);

foreach ($files as $file) {
    $path = $file[0];
    $content = file_get_contents($path);
    
    // Replace things like bg-white dark:bg-[#1e1b4b]/10 -> bg-white/10 dark:bg-[#1e1b4b]/10
    // Keep hover and group-hover states correctly
    $content = preg_replace_callback(
        '/(hover:|group-hover:)?bg-white dark:(hover:|group-hover:)?bg-\\[\\#1e1b4b\\]\\/([0-9]+)/',
        function ($matches) {
            $prefix = $matches[1] ? $matches[1] : '';
            $darkPrefix = $matches[2] ? $matches[2] : '';
            $opacity = $matches[3];
            return "{$prefix}bg-white/{$opacity} dark:{$darkPrefix}bg-[#1e1b4b]/{$opacity}";
        },
        $content
    );

    file_put_contents($path, $content);
}
echo "Surveyor opacity fixed.\n";
