<?php

namespace Core;

/**
 * @method static void get(string $route, string $controller, string $method, string $type = null)
 * @method static void post(string $route, string $controller, string $method, string $type = null)
 * @method static void put(string $route, string $controller, string $method, string $type = null)
 * @method static void delete(string $route, string $controller, string $method, string $type = null)
 */
class Router
{
    private static $routes;
    private static $regexes;
    private static $key = '__route__';

    public static function __callStatic($request, $args)
    {
        $args[1] = !empty(SPACE) ? 'App\\' . SPACE . '\Controller\\' . $args[1] : "App\Controller\\{$args[1]}";
        $args[3] = !empty($args[3]) ? $args[3] : 'api';
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
        foreach (self::$regexes as $key => $value) {
            if (preg_match($value, $route, $args)) {
                array_shift($args);
                $action = self::$routes[$key];
                if ($_SERVER['REQUEST_METHOD'] === $action['request'] && method_exists($action['controller'], $action['method'])) {
                    return array(
                        'response' => call_user_func_array(array(new $action['controller'], $action['method']), $args),
                        'type' => $action['type']
                    );
                }
            }
        }
        mException()::raise("No route defined for this URI.");
        return false;
    }
}
