<?php

require __DIR__ . '/Vendors/autoload.php';

use Core\M;

M::App()->init(realpath(__DIR__ . '/../'));

if (M::App()->getConfig('app')->domain != $_SERVER['SERVER_NAME']) {
    error_reporting(E_ALL);
    M::App()->setEnv('dev');
} else {
    error_reporting(0);
}

M::App()->spacePublic('/');

require __DIR__ . '/../app/Router.php';

M::App()->run();