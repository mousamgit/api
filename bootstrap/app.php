<?php

require_once __DIR__ . '/../vendor/autoload.php';

$config = require_once __DIR__ . '/../config/database.php';

use Illuminate\Database\Capsule\Manager as Capsule;

if (!function_exists('dd')) {
    function dd(...$vars)
    {
        echo "<pre>";

        foreach ($vars as $v) {
            if (is_array($v) || is_object($v)) {
                print_r($v);
            } else {
                var_dump($v);
            }
            echo "\n";
        }

        echo "</pre>";

        die(1); // Halts script execution
    }
}


$capsule = new Capsule;

$capsule->addConnection($config['database']);

$capsule->setAsGlobal();
$capsule->bootEloquent();
