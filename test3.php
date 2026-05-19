<?php
require 'vendor/autoload.php';
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

try {
    $m = new ImageManager(new Driver());
    $i = $m->read('test.jpg');
    $i->resize(300, 300);
    $i->save('test_300.jpg');
    echo 'OK read';
} catch (\Exception $e) {
    echo $e->getMessage();
}
