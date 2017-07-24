<?php

namespace Core;

/**
 * @method static Route get(string $route, string $class, string $method)
 * @method static Route post(string $route, string $class, string $method)
 * @method static Route put(string $route, string $class, string $method)
 * @method static Route delete(string $route, string $class, string $method)
 * @method static Route options(string $route, string $class, string $method)
 * @method static Route head(string $route, string $class, string $method)
 */
class Route
{
    private static $routeKey = '__route__';
    private static $routes = array();
    private static $regexes = array();

    public static function __callStatic($httpMethod, $arguments)
    {
        $route = $arguments[0];
        $controller = (App::space()) ? App::space() . '\Controller\\' . $arguments[1] : 'Controller\\' . $arguments[1];
        $controller = 'App\\' . $controller;
        $method = $arguments[2];
        $httpMethod = strtoupper($httpMethod);

        self::$routes[] = array(
            'route' => $route,
            'controller' => $controller,
            'method' => $method,
            'httpMethod' => $httpMethod
        );

        self::$regexes[] = "#^{$route}\$#";
    }

    public static function dispatch()
    {
        $routeDef = array();
        $localServer = (SP_DIR === '/') ? $_SERVER['REQUEST_URI'] : '/';
        $route = isset($_GET[self::$routeKey]) ? $_GET[self::$routeKey] : $localServer;

        foreach (self::$regexes as $ind => $regex) {
            if (preg_match($regex, $route, $arguments)) {
                array_shift($arguments);
                $def = self::$routes[$ind];
                if ($_SERVER['REQUEST_METHOD'] != $def['httpMethod']) {
                    continue;
                } elseif (method_exists($def['controller'], $def['method'])) {
                    $routeDef = array(
                        'controller' => $def['controller'],
                        'method' => $def['method'],
                        'args' => $arguments
                    );
                    break;
                }
            }
        }

        return call_user_func_array(array(new $routeDef['controller'], $routeDef['method']), $routeDef['args']);
    }

    public static function load()
    {
        require BASE_PATH . '/src/Boot/Router.php';
    }
}
