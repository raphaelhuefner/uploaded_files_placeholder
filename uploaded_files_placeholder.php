<?php

namespace Raphaelhuefner\UploadedFilesPlaceholder;

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}
else {
    require_once __DIR__ . '/src/SimpleAutoloader.php';
    spl_autoload_register([__NAMESPACE__ . '\\SimpleAutoloader', 'loader'], TRUE);
}

$script = new Script();
$script->run();
