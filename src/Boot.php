<?php

require __DIR__ . '/Vendors/autoload.php';

use Src\Core\M;

M::App()->init(realpath(__DIR__ . '/../'));

if (M::App()->getConfig('app')->domain == $_SERVER['SERVER_NAME']) {
    error_reporting(0);
} else {
    error_reporting(E_ALL);
    M::App()->setEnv('dev');
}

require __DIR__ . '/../app/Router.php';

M::App()->run();