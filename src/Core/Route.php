<?php

namespace Src\Core;

class Route
{
    const ROUTE_KEY = '__route__';

    private static $instance = null;

    private $routes = array();
    private $regexes = array();
    private $route = null;

    public function get($route, $controller, $method)
    {
        $this->addRoute($route, $controller, $method, 'GET');
    }

    public function post($route, $controller, $method)
    {
        $this->addRoute($route, $controller, $method, 'POST');
    }

    public function put($route, $controller, $method)
    {
        $this->addRoute($route, $controller, $method, 'PUT');
    }

    public function delete($route, $controller, $method)
    {
        $this->addRoute($route, $controller, $method, 'DELETE');
    }

    public function dispatch()
    {
        $routeDef = array();
        $this->route = isset($_GET[self::ROUTE_KEY]) ? $_GET[self::ROUTE_KEY] : '/';

        foreach ($this->regexes as $ind => $regex) {
            if (preg_match($regex, $this->route, $arguments)) {
                array_shift($arguments);
                $def = $this->routes[$ind];
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

        return call_user_func_array(array($routeDef['controller'], $routeDef['method']), $routeDef['args']);
    }

    private function addRoute($route, $controller, $method, $httpMethod)
    {
        $controller = (M::App()->space()) ? 'App\\' . M::App()->space() . '\Controller\\' . $controller : 'App\Controller\\' . $controller;
        $this->routes[] = array(
            'route' => $route,
            'controller' => $controller,
            'method' => $method,
            'httpMethod' => $httpMethod
        );
        $this->regexes[] = "#^{$route}\$#";
    }

    public static function getInstance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }
        return self::$instance;
    }
}
