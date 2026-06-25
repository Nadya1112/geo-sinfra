x`<?php
$files = [
    'resources/views/emails/darurat_notification.blade.php',
    'resources/views/admin/detail-infrastruktur.blade.php',
    'resources/views/admin/activity.blade.php',
    'resources/views/admin/statistik.blade.php',
    'resources/views/admin/users.blade.php'
];

function replaceInFile($path, $search, $replace) {
    if (file_exists($path)) {
        $content = file_get_contents($path);
        $newContent = str_replace($search, $replace, $content);
        if ($content !== $newContent) {
            file_put_contents($path, $newContent);
            echo "Updated: $path\n";
        }
    } else {
        echo "Not found: $path\n";
    }
}

foreach ($files as $f) {
    replaceInFile($f, "route('kabid.", "route('tim_teknis.");
    replaceInFile($f, "Kabid", "Tim Teknis");
    replaceInFile($f, "KABID", "TIM TEKNIS");
    replaceInFile($f, "'kabid'", "'tim_teknis'");
    replaceInFile($f, "jumlahKabid", "jumlahTimTeknis");
    replaceInFile($f, "kabid", "tim_teknis");
}
echo "Done part 2.\n";
