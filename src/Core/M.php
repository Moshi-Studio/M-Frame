<?php

namespace Core;

class M
{
    public static function App()
    {
        return App::getInstance();
    }

    public static function Cache()
    {
        return Cache::getInstance();
    }

    public static function Database()
    {
        return Database::getInstance();
    }

    public static function Response()
    {
        return Response::getInstance();
    }

    public static function Route()
    {
        return Route::getInstance();
    }

    public static function Session()
    {
        return Session::getInstance();
    }
}
