<?php

namespace Core;

use Data\Cache;
use Data\Database;
use Data\Session;

class App
{
    private static $config;

    public static function setEnv($env)
    {
        $name = 'app-' . $env;
        self::$config->app = self::$config->$name;
    }

    public static function setConfig($key, $value)
    {
        self::$config->$key = $value;
    }

    public static function getConfig($key)
    {
        if (!empty($key)) {
            return isset(self::$config->$key) ? self::$config->$key : null;
        } else {
            return self::$config;
        }
    }

    public static function space()
    {
        if ($spaces = self::getConfig('app')->spaces) {
            $uris = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
            $uris = str_replace(SP_DIR, '/', $uris);
            $uri = strtolower(explode('/', $uris)[1]);
            $spaces = explode(',', $spaces);
            if ($uris !== '/' && in_array($uri, $spaces)) {
                return ucfirst($uri);
            }
            return 'Web';
        }
        return false;
    }

    public static function employ($database = null, $session = null, $cache = null, $constants = null)
    {
        if (!empty($database)) {
            M::set('database', new Database(
                    self::getConfig($database)->type,
                    self::getConfig($database)->name,
                    self::getConfig($database)->host,
                    self::getConfig($database)->username,
                    self::getConfig($database)->password,
                    self::getConfig($database)->port
                )
            );
        }

        if (!empty($session)) {
            M::set('session', new Session(
                    self::getConfig($session)->host,
                    self::getConfig($session)->port,
                    self::getConfig($session)->expiry,
                    self::getConfig($session)->namespace
                )
            );
        }

        if (!empty($cache)) {
            M::set('cache', new Cache(
                    self::getConfig($cache)->host,
                    self::getConfig($cache)->port,
                    self::getConfig($cache)->expiry,
                    self::getConfig($cache)->namespace
                )
            );
        }

        if (!empty($constants)) {
            self::loadConstants(self::getConfig($constants));
        }
    }

    public static function spacePublic($dir)
    {
        DEFINE('SP_DIR', $dir);
        DEFINE('SP_FILES', self::space() ? SP_DIR . 'files/' . strtolower(self::space()) . '/' : SP_DIR . 'files/');
    }

    public static function run()
    {
        Route::load();
        Response::handler(Route::dispatch());
    }

    public static function loadConfig()
    {
        self::$config = new \stdClass;
        $file = BASE_PATH . '/src/Boot/Config.ini';
        $parsed_array = parse_ini_file($file, true);
        foreach ($parsed_array as $key => $value) {
            if (!is_array($value)) {
                self::$config->$key = $value;
            } else {
                if (!isset(self::$config->$key)) {
                    self::$config->$key = new \stdClass;
                }
                foreach ($value as $innerKey => $innerValue) {
                    self::$config->$key->$innerKey = $innerValue;
                }
            }
        }
    }

    private static function loadConstants($constants)
    {
        foreach ($constants as $key => $value) {
            define(strtoupper($key), $value);
        }
    }
}
