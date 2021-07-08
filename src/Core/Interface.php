<?php

use Core\App;
use Core\Cache;
use Core\Database;
use Core\MException;
use Core\Response;
use Core\Router;
use Core\Session;

/**
 * @return Cache
 */
function mCache()
{
    return App::data('cache');
}

/**
 * @return Database
 */
function mDatabase()
{
    return App::data('database');
}

/**
 * @param array $data
 * @return void
 */
function mDump($data)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die();
}

/**
 * @param string $data
 * @param int $code
 * @return void
 */
function mError($data, $code)
{
    $data['code'] = $code;
    return $data;
}

/**
 * @return MException
 */
function mException()
{
    return App::data('exception');
}

/**
 * @param string $name
 * @param string message
 * @return void
 */
function mLog($name, $message)
{
    $pathname = __DIR__ . '/../../logs/';
    if (!is_dir($pathname)) {
        mkdir($pathname, 0777, true);
    }

    $filename = $pathname . $name . '.log';

    if (null === $message) {
        unlink($filename);
    } else {
        error_log($message . "\n", 3, $filename);
    }
}

/**
 * @param string $url
 * @param int $code
 * @return void
 */
function mRedirect($url, $code = 302)
{
    header("Status: {$code}");
    header("Location: {$url}");
    exit();
}

/**
 * @return Response
 */
function mResponse()
{
    return App::data('response');
}

/**
 * @return Router
 */
function mRouter()
{
    return App::data('router');
}

/**
 * @return Session
 */
function mSession()
{
    return App::data('session');
}
