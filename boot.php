<?php

require __DIR__ . '/src/Vendors/autoload.php';

use Core\App;

App::init();

App::load();

if (App::config('app')->domain != $_SERVER['SERVER_NAME']) {
    App::env('dev');
    error_reporting(E_ALL);
}

App::employ(
    // App::config('app')->database,
    // App::config('app')->session,
    // App::config('app')->cache,
    // App::config('app')->constants
);

App::run();
