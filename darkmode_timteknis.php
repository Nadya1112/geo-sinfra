<?php
$views = glob('resources/views/tim_teknis/*.blade.php');

$initScript = <<<HTML
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        navy: { 50:'#f4f4fa', 100:'#e9e9f3', 200:'#c7c8e3', 300:'#9fb3c8', 400:'#829ab1', 500:'#6366f1', 600:'#486581', 700:'#334e68', 800:'#1e1b4b', 900:'#0f0e2c', 950:'#070617' },
                        gold: { 50:'#fdfbf7', 100:'#fbf7ed', 200:'#eed9b9', 300:'#e5c292', 400:'#dba665', 500:'#c5a059', 600:'#b38f4a', 700:'#9d7c3d', 800:'#7c5327', 900:'#644422', 950:'#382310' }
                    }
                }
            }
        }
    </script>
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
HTML;

foreach($views as $file) {
    $content = file_get_contents($file);
    
    // Replace the existing tailwind.config block with our new initScript
    $content = preg_replace('/<script>\s*tailwind\.config = \{.*?\<\/script>/s', $initScript, $content);

    // Apply dark mode classes safely by replacing the explicit class strings
    $replacements = [
        'bg-slate-50' => 'bg-slate-50 dark:bg-[#0f0e2c]',
        'bg-white' => 'bg-white dark:bg-[#1e1b4b]',
        'text-slate-800' => 'text-slate-800 dark:text-white',
        'text-navy-900' => 'text-navy-900 dark:text-white',
        'border-slate-100' => 'border-slate-100 dark:border-white/10',
        'border-slate-200' => 'border-slate-200 dark:border-white/20',
        '<body class="bg-slate-50 dark:bg-[#0f0e2c] flex h-screen overflow-hidden text-slate-800 dark:text-white text-left">' => '<body class="bg-slate-50 dark:bg-[#0f0e2c] flex h-screen overflow-hidden text-slate-800 dark:text-white text-left font-sans dark:bg-navy-950 transition-colors duration-300">',
    ];

    // Some custom replacements to prevent double application if already applied
    foreach($replacements as $search => $replace) {
        // Prevent double applying
        if(!str_contains($content, $replace)) {
             $content = str_replace($search, $replace, $content);
        }
    }

    // Fix double classes if any
    $content = str_replace('dark:bg-[#0f0e2c] dark:bg-[#0f0e2c]', 'dark:bg-[#0f0e2c]', $content);
    $content = str_replace('dark:bg-[#1e1b4b] dark:bg-[#1e1b4b]', 'dark:bg-[#1e1b4b]', $content);
    $content = str_replace('dark:text-white dark:text-white', 'dark:text-white', $content);

    file_put_contents($file, $content);
    echo "Processed: " . $file . "\n";
}

// Sidebar doesn't need head script, just dark mode classes
$sidebar = 'resources/views/tim_teknis/partials/sidebar.blade.php';
if(file_exists($sidebar)) {
    $content = file_get_contents($sidebar);
    $replacements = [
        'bg-slate-50' => 'bg-slate-50 dark:bg-[#0f0e2c]',
        'bg-white' => 'bg-white dark:bg-[#1e1b4b]',
        'text-slate-800' => 'text-slate-800 dark:text-white',
        'text-navy-900' => 'text-navy-900 dark:text-white',
        'border-slate-100' => 'border-slate-100 dark:border-white/10',
        'border-slate-200' => 'border-slate-200 dark:border-white/20',
    ];
    foreach($replacements as $search => $replace) {
        if(!str_contains($content, $replace)) {
             $content = str_replace($search, $replace, $content);
        }
    }
    file_put_contents($sidebar, $content);
    echo "Processed: " . $sidebar . "\n";
}
