<?php

spl_autoload_register(function ($class) {

    $baseDir = __DIR__ . '/../';

    $paths = [
        $baseDir . 'admin/Models/',
        $baseDir . 'admin/Controllers/',
        $baseDir . 'app/Controllers/',
        $baseDir . 'core/'
    ];

    foreach ($paths as $path) {
        $file = $path . $class . '.php';

        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

