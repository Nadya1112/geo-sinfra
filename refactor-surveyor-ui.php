<?php
$files = glob(__DIR__ . '/resources/views/surveyor/*.blade.php');

$replacements = [
    'bg-white' => 'bg-white dark:bg-[#1e1b4b]',
    'border-slate-100' => 'border-slate-100 dark:border-white/10',
    'border-slate-200' => 'border-slate-200 dark:border-white/20',
    'text-navy-900' => 'text-navy-900 dark:text-white',
    'bg-slate-50' => 'bg-slate-50 dark:bg-[#0f0e2c]',
    'bg-navy-50' => 'bg-navy-50 dark:bg-navy-900',
    'text-slate-700' => 'text-slate-700 dark:text-slate-300',
    'text-slate-600' => 'text-slate-600 dark:text-slate-400',
    'hover:bg-slate-50' => 'hover:bg-slate-50 dark:hover:bg-white/5',
];

foreach ($files as $file) {
    $content = file_get_contents($file);
    $original = $content;
    
    foreach ($replacements as $search => $replace) {
        // Jangan timpa jika sudah di-replace
        if (strpos($content, $replace) === false) {
            $content = str_replace($search, $replace, $content);
        }
    }

    if ($original !== $content) {
        file_put_contents($file, $content);
        echo "Diperbarui utility: " . basename($file) . "\n";
    }
}
echo "Selesai.\n";
