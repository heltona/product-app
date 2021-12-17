<?php
use App\Http\Dispatcher;
use App\Models\Product;
use App\Repositories\ProductDataMapper;

require __DIR__ . "/../../vendor/autoload.php";

// I seems compliant enough with PSR-4
spl_autoload_register(function ($className) {
    $classPath = __DIR__ . "/../../" . str_replace("\\", "/", $className) . '.php';
    if (file_exists($classPath))
        require_once $classPath;
});



// just because we don't use framework, it is no reason enough to write transaction script
$dispatcher = new Dispatcher();
$dispatcher->run();



