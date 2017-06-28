<?php

require __DIR__ . '/../Vendors/autoload.php';

use Core\App;

App::loadConfig();

if (App::getConfig('app')->domain != $_SERVER['SERVER_NAME']) {
    error_reporting(E_ALL);
    App::setEnv('dev');
} else {
    error_reporting(0);
}

App::spacePublic('/');

App::run();