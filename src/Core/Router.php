<?php

namespace Core;

/**
 * @method static void get(string $route, string $controller, string $method, string $type = null)
 * @method static void post(string $route, string $controller, string $method, string $type = null)
 * @method static void put(string $route, string $controller, string $method, string $type = null)
 * @method static void delete(string $route, string $controller, string $method, string $type = null)
 * @method static void options(string $route, string $controller, string $method, string $type = null)
 * @method static void head(string $route, string $controller, string $method, string $type = null)
 */
class Router
{
    private static $routes;
    private static $regexes;
    private static $key = '__route__';

    public static function __callStatic($request, $args)
    {
        $args[1] = !empty(SPACE) ? 'App\\' . SPACE . '\Controller\\' . $args[1] : "App\Controller\\{$args[1]}";
        $args[3] = !empty($args[3]) ? $args[3] : 'json';

        self::$routes[] = array(
            'route' => $args[0],
            'controller' => $args[1],
            'method' => $args[2],
            'type' => $args[3],
            'request' => strtoupper($request)
        );
        self::$regexes[] = "#^{$args[0]}\$#";
    }

    public static function direct()
    {
        $localServer = App::config('app')->webroot === '/' ? $_SERVER['REQUEST_URI'] : '/';
        $route = isset($_GET[self::$key]) ? $_GET[self::$key] : $localServer;
        $call = false;

        if ($route != strtolower($route)) {
            header('Status: 301');
            header('Location: ' . strtolower($route));
            exit();
        }

        foreach (self::$regexes as $key => $value) {
            if (preg_match($value, $route, $args)) {
                array_shift($args);
                $action = self::$routes[$key];
                if (self::getRequestMethod() === $action['request'] && method_exists($action['controller'], $action['method'])) {
                    if ($_SERVER['REQUEST_METHOD'] == 'HEAD') {
                        $action['type'] = 'head';
                    }
                    $call = array(
                        'response' => call_user_func_array(array(new $action['controller'], $action['method']), $args),
                        'type' => $action['type']
                    );
                    break;
                }
            }
        }

        if (empty($call)) {
            mException()::raise("No route defined for this URI: " . $route . " " . $_SERVER['REQUEST_METHOD']);
        }

        return $call;
    }

    private static function getRequestMethod()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($_SERVER['REQUEST_METHOD'] == 'HEAD') {
            ob_start();
            $method = 'GET';
        }

        return $method;
    }
}
