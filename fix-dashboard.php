<?php
$file = __DIR__ . '/resources/views/surveyor/dashboard.blade.php';
$content = file_get_contents($file);

// 1. Add darkMode: 'class'
if (strpos($content, "tailwind.config = {") !== false && strpos($content, "darkMode: 'class'") === false) {
    $content = str_replace("tailwind.config = {\n            theme: {", "tailwind.config = {\n            darkMode: 'class',\n            theme: {", $content);
}

// 2. Add init script
$initScript = "
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>";
if (strpos($content, "localStorage.getItem('theme')") === false) {
    $content = str_replace("</script>\n    <style>", "</script>" . $initScript . "\n    <style>", $content);
}

// 3. Body dark mode
$content = preg_replace('/<body class="([^"]+)"/', '<body class="$1 dark:bg-navy-950 dark:text-white transition-colors duration-300"', $content);

// 4. UI utility replacements
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
foreach ($replacements as $search => $replace) {
    // Basic str_replace, but we have to be careful not to duplicate if it somehow exists
    if (strpos($content, $replace) === false) {
        $content = str_replace($search, $replace, $content);
    }
}

// 5. Stat Numbers Vibrant Colors
$content = str_replace(
    '<h3 class="text-3xl font-black text-navy-900 dark:text-white">{{ $totalSurvey }}',
    '<h3 class="text-3xl font-black text-blue-600 dark:text-blue-400">{{ $totalSurvey }}',
    $content
);
$content = str_replace(
    '<h3 class="text-3xl font-black text-navy-900 dark:text-white">{{ $waitingValidation }}',
    '<h3 class="text-3xl font-black text-orange-600 dark:text-orange-400">{{ $waitingValidation }}',
    $content
);
$content = str_replace(
    '<h3 class="text-3xl font-black text-navy-900 dark:text-white">{{ $verifiedAI }}',
    '<h3 class="text-3xl font-black text-emerald-600 dark:text-emerald-400">{{ $verifiedAI }}',
    $content
);
$content = str_replace(
    '<h3 class="text-3xl font-black text-red-600">{{ $totalRejected }}',
    '<h3 class="text-3xl font-black text-red-600 dark:text-red-400">{{ $totalRejected }}',
    $content
);
$content = str_replace(
    '<h3 class="text-2xl font-black text-navy-900 dark:text-white">{{ $totalTugas }}</h3>',
    '<h3 class="text-2xl font-black text-indigo-600 dark:text-indigo-400">{{ $totalTugas }}</h3>',
    $content
);
$content = str_replace(
    '<h3 class="text-2xl font-black text-orange-600">{{ $tugasMenunggu }}</h3>',
    '<h3 class="text-2xl font-black text-orange-600 dark:text-orange-400">{{ $tugasMenunggu }}</h3>',
    $content
);
$content = str_replace(
    '<h3 class="text-2xl font-black text-emerald-600">{{ $tugasSelesai }}</h3>',
    '<h3 class="text-2xl font-black text-emerald-600 dark:text-emerald-400">{{ $tugasSelesai }}</h3>',
    $content
);

// 6. Fix "Wilayah Tugas Anda" invisible text in light mode
// The button
$content = str_replace(
    '<button onclick="toggleModal(\'territoryModal\')" class="px-4 py-2 bg-white dark:bg-[#1e1b4b]/5 hover:bg-white dark:bg-[#1e1b4b]/10 border border-white/10 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all backdrop-blur-md">',
    '<button onclick="toggleModal(\'territoryModal\')" class="px-4 py-2 bg-white dark:bg-[#1e1b4b]/5 hover:bg-white dark:bg-[#1e1b4b]/10 text-navy-900 dark:text-white border border-white/10 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all backdrop-blur-md">',
    $content
);

// The list items
$content = str_replace(
    '<h5 class="text-sm font-black uppercase tracking-wider">{{ $assignedKec->nama_kecamatan }}</h5>',
    '<h5 class="text-sm font-black text-navy-900 dark:text-white uppercase tracking-wider">{{ $assignedKec->nama_kecamatan }}</h5>',
    $content
);

file_put_contents($file, $content);
echo "Berhasil!";
