<?php
$files = array_merge(
    glob('resources/views/tim_teknis/*.blade.php'),
    glob('resources/views/tim_teknis/partials/*.blade.php'),
    ['app/Services/WhatsAppService.php', 'resources/views/emails/darurat_notification.blade.php']
);
foreach($files as $f) {
    if(file_exists($f)) {
        $content = file_get_contents($f);
        $content = str_ireplace('Kepala Bidang', 'Tim Teknis', $content);
        file_put_contents($f, $content);
        echo 'Updated: ' . $f . "\n";
    }
}
