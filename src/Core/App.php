<?php

namespace Core;

class App
{
    private static $app;

    public static function init()
    {
        define('BASE_PATH', __DIR__ . '/../../');
        self::$app['data']['exception'] = new MException;
        self::$app['data']['response'] = new Response;
        self::$app['data']['router'] = new Router;
    }

    public static function load()
    {
        self::$app['config'] = parse_ini_file(BASE_PATH . 'config.ini', true, INI_SCANNER_TYPED);
    }

    public static function env($env)
    {
        self::$app['config']['app'] = self::config("app-{$env}");
    }

    public static function config($key)
    {
        if (!array_key_exists($key, self::$app['config'])) {
            mException()::raise("No {$key} config is bound in the container.");
        }

        return (object)self::$app['config'][$key];
    }

    public static function data($key)
    {
        if (!array_key_exists($key, self::$app['data'])) {
            mException()::raise("No {$key} data is bound in the container.");
        }

        return self::$app['data'][$key];
    }

    public static function employ($database = NULL, $session = NULL, $cache = NULL, $constants = NULL)
    {
        if (!is_null($database)) {
            self::$app['data']['database'] = new Database(
                self::config($database)->type,
                self::config($database)->host,
                self::config($database)->name,
                self::config($database)->port,
                self::config($database)->username,
                self::config($database)->password
            );
        }

        if (!is_null($session)) {
            self::$app['data']['session'] = new Session(
                self::config($session)->host,
                self::config($session)->port,
                self::config($session)->expiry,
                self::config($session)->namespace
            );
        }

        if (!is_null($cache)) {
            self::$app['data']['cache'] = new Cache(
                self::config($cache)->host,
                self::config($cache)->port,
                self::config($cache)->expiry,
                self::config($cache)->namespace
            );
        }

        if (!is_null($constants)) {
            foreach (self::config($constants) as $key => $value) {
                define(strtoupper($key), $value);
            }
        }
    }

    public static function run()
    {
        define('SPACE', self::currentSpace());
        define('SP_DIR', strtolower(SPACE));
        define('SP_FILES', self::currentFiles());

        require BASE_PATH . 'src/App/Run.php';

        mResponse()::handler(
            mRouter()::direct()
        );
    }

    private static function currentSpace()
    {
        if (empty(self::config('app')->spaces)) {
            return '';
        }

        $space = 'Web';
        $spaces = explode(',', self::config('app')->spaces);
        $uri = str_replace(self::config('app')->webroot, '/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $firstUri = strtolower(explode('/', $uri)[1]);

        if ($uri !== '/' && in_array($firstUri, $spaces)) {
            $space = ucfirst($firstUri);
        }

        return $space;
    }

    private static function currentFiles()
    {
        if (empty(self::config('app')->spaces)) {
            return self::config('app')->webroot . 'files/';
        }

        return self::config('app')->webroot . 'files/' . strtolower(self::currentSpace()) . '/';
    }
}
