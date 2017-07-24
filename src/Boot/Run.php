<?php

DEFINE('BASE_PATH', realpath(__DIR__ . '/../../'));

require BASE_PATH . '/src/Vendors/autoload.php';

use Core\App;

App::loadConfig();
setlocale(LC_ALL, 'es_MX');

if (App::getConfig('app')->domain != $_SERVER['SERVER_NAME']) {
    error_reporting(E_ALL);
    App::setEnv('dev');
}

App::spacePublic('/');

App::employ(
    App::getConfig('app')->database,
    App::getConfig('app')->session,
    App::getConfig('app')->cache,
    App::getConfig('app')->constants
);

App::run();
