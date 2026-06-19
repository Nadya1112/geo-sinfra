<?php
$files = [
    'app/Http/Controllers/TimTeknis/TimTeknisController.php',
    'app/Http/Controllers/Admin/AdminController.php',
    'app/Http/Controllers/Surveyor/SurveyorController.php',
    'app/Models/User.php',
    'app/Models/AnalisisAi.php',
    'app/Observers/InfrastrukturObserver.php',
    'app/Services/WhatsAppService.php',
    'routes/web.php',
    'resources/views/surveyor/show.blade.php'
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

// 1. TimTeknisController.php
replaceInFile($files[0], 'namespace App\Http\Controllers\Kabid;', 'namespace App\Http\Controllers\TimTeknis;');
replaceInFile($files[0], 'class KabidController extends Controller', 'class TimTeknisController extends Controller');
replaceInFile($files[0], "view('kabid.", "view('tim_teknis.");
replaceInFile($files[0], "route('kabid.", "route('tim_teknis.");

// 2. AdminController.php
replaceInFile($files[1], "'kabid'", "'tim_teknis'");
replaceInFile($files[1], 'Kabid', 'Tim Teknis');
replaceInFile($files[1], 'KABID', 'TIM TEKNIS');
replaceInFile($files[1], 'jumlahKabid', 'jumlahTimTeknis');

// 3. SurveyorController.php
replaceInFile($files[2], 'Kabid', 'Tim Teknis');
replaceInFile($files[2], 'kabid', 'tim_teknis');

// 4. User.php
replaceInFile($files[3], "'kabid'", "'tim_teknis'");
replaceInFile($files[3], 'isKabid', 'isTimTeknis');
replaceInFile($files[3], 'kabid', 'tim_teknis');

// 5. AnalisisAi.php
replaceInFile($files[4], "'id_kabid'", "'id_tim_teknis'");

// 6. InfrastrukturObserver.php
replaceInFile($files[5], "env('KABID_EMAIL'", "env('TIM_TEKNIS_EMAIL'");

// 7. WhatsAppService.php
replaceInFile($files[6], "KABID_WA_NUMBER", "TIM_TEKNIS_WA_NUMBER");
replaceInFile($files[6], "Kabid", "Tim Teknis");
replaceInFile($files[6], "route('kabid.", "route('tim_teknis.");

// 8. routes/web.php
replaceInFile($files[7], "role:kabid", "role:tim_teknis");
replaceInFile($files[7], "prefix('kabid')", "prefix('tim-teknis')");
replaceInFile($files[7], "App\Http\Controllers\Kabid\KabidController", "App\Http\Controllers\TimTeknis\TimTeknisController");
replaceInFile($files[7], "name('kabid.", "name('tim_teknis.");
replaceInFile($files[7], "KABID", "TIM TEKNIS");

// 9. surveyor/show.blade.php
replaceInFile($files[8], "Di-ACC Kabid", "Di-ACC Tim Teknis");
replaceInFile($files[8], "Catatan Eksekutif (Kabid)", "Catatan Eksekutif (Tim Teknis)");

// Now for the views in tim_teknis folder
$views = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('resources/views/tim_teknis'));
foreach ($views as $view) {
    if (!$view->isDir()) {
        $path = $view->getPathname();
        replaceInFile($path, "route('kabid.", "route('tim_teknis.");
        replaceInFile($path, "include('kabid.", "include('tim_teknis.");
        replaceInFile($path, "Kabid", "Tim Teknis");
        replaceInFile($path, "KABID", "TIM TEKNIS");
        replaceInFile($path, "kabid", "tim_teknis");
    }
}
echo "Done.\n";
