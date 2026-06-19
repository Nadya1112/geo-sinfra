<?php
$views = glob('resources/views/tim_teknis/*.blade.php');

$replacements = [
    // Emerald
    'bg-emerald-50 text-emerald-600 border-emerald-100' => 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border-emerald-100 dark:border-emerald-500/20',
    'bg-emerald-50 text-emerald-600 text-emerald-600' => 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400',
    'bg-emerald-50 border border-emerald-100 text-emerald-700' => 'bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400',
    'border-emerald-500 bg-emerald-50' => 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20',
    'bg-emerald-50 text-emerald-600 border border-emerald-100' => 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-500/20',
    'bg-emerald-50/50' => 'bg-emerald-50/50 dark:bg-[#0f0e2c]',
    
    // Rose / Red
    'bg-rose-50 border border-rose-100 text-rose-700' => 'bg-rose-50 dark:bg-rose-900/20 border border-rose-100 dark:border-rose-500/20 text-rose-700 dark:text-rose-400',
    'bg-rose-50 text-rose-600 border border-rose-200' => 'bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-500/20',
    'bg-red-50 text-red-600 border border-red-100' => 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 border border-red-100 dark:border-red-500/20',
    'bg-red-50 text-red-600 text-red-600' => 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400',
    
    // Orange / Amber
    'bg-orange-50 text-orange-600 border border-orange-100' => 'bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400 border border-orange-100 dark:border-orange-500/20',
    'bg-orange-50 text-orange-600 text-orange-600' => 'bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400',
    'bg-amber-50 text-amber-600 border border-amber-100' => 'bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 border border-amber-100 dark:border-amber-500/20',
    
    // Blue / Indigo
    'bg-blue-50 text-blue-600 border border-blue-100' => 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-500/20',
    'bg-indigo-50 border-b border-indigo-100' => 'bg-indigo-50 dark:bg-indigo-900/20 border-b border-indigo-100 dark:border-indigo-500/20',
    'bg-indigo-50 border border-indigo-100' => 'bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-500/20',
    'text-indigo-900' => 'text-indigo-900 dark:text-indigo-400',
    
    // General
    'bg-white text-slate-400 hover:bg-slate-100 border border-slate-200' => 'bg-white dark:bg-[#1e1b4b] text-slate-400 hover:bg-slate-100 dark:hover:bg-[#0f0e2c] border border-slate-200 dark:border-white/20',
    'bg-slate-50 border border-slate-200' => 'bg-slate-50 dark:bg-[#0f0e2c] border border-slate-200 dark:border-white/20',
];

foreach($views as $file) {
    $content = file_get_contents($file);
    foreach($replacements as $search => $replace) {
        if(!str_contains($content, $replace)) {
             $content = str_replace($search, $replace, $content);
        }
    }
    file_put_contents($file, $content);
}
echo "Done replacing bright colors.\n";
