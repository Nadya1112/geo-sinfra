<?php
$file = 'c:/laragon1/laragon/www/geo-sinfra/resources/views/surveyor/dashboard.blade.php';
$c = file_get_contents($file);

// 1. Remove dark classes
$c = preg_replace('/\bdark:[^\s"\']+/', '', $c);

// 2. Add responsive CSS
if(strpos($c, '@media (min-width: 768px)') === false) { 
    $style = "<style>\n    @media (min-width: 768px) { html { font-size: 14px; } }\n    @media (max-width: 767px) { html { font-size: 12px; } }\n</style>\n</head>";
    $c = str_replace('</head>', $style, $c); 
}

// 3. Fix header padding
$c = preg_replace('/<header class="([^"]*)px-8 py-5([^"]*)">/', '<header class="$1sticky top-0 px-4 pl-16 md:px-8 py-4$2">', $c);

// 4. Fix body padding
$c = str_replace('class="flex-1 overflow-y-auto custom-scrollbar p-8', 'class="flex-1 overflow-y-auto custom-scrollbar p-4 md:p-8', $c);

// 5. REMOVE WHITE BLUR FROM WELCOME CARD
$c = str_replace('<div class="absolute -left-10 -bottom-10 w-64 h-64 bg-navy-50  rounded-full blur-3xl pointer-events-none"></div>', '', $c);

// Wait, the blur original was dark:bg-navy-9000/20, let's remove it safely using regex just in case
$c = preg_replace('/<div class="absolute -left-10 -bottom-10 w-64 h-64 bg-navy-50[^>]*><\/div>/', '', $c);

file_put_contents($file, $c);
echo "Dashboard fixed!";
