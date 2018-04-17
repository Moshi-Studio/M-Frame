<?php

/**
 * @return \Core\Cache
 */
function mCache()
{
    return \Core\App::data('cache');
}

/**
 * @return \Core\Database
 */
function mDatabase()
{
    return \Core\App::data('database');
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
 * @return \Core\MException
 */
function mException()
{
    return \Core\App::data('exception');
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
 * @return \Core\Response
 */
function mResponse()
{
    return \Core\App::data('response');
}

/**
 * @return \Core\Router
 */
function mRouter()
{
    return \Core\App::data('router');
}

/**
 * @return \Core\Session
 */
function mSession()
{
    return \Core\App::data('session');
}
