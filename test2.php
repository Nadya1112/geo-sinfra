<?php
require 'vendor/autoload.php';
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

try {
    $m = new ImageManager(new Driver());
    $i = $m->createImage(100, 100);
    $i->resize(50, 50);
    $i->save('test.jpg');
    echo 'OK';
} catch (\Exception $e) {
    echo $e->getMessage();
}
