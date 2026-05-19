<?php
require __DIR__.'/vendor/autoload.php';

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

try {
    $manager = new ImageManager(new Driver());
    $image = $manager->create(100, 100);
    $image->resize(50, 50);
    echo "Resize success\n";
    echo get_class($image) . "\n";
    $encoded = $image->toJpeg();
    echo "toJpeg success\n";
} catch (\Exception $e) {
    echo $e->getMessage();
}
