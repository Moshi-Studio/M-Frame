<?php

namespace Core;

use Exception;

class M
{
    private static $registry = [];

    public static function set($key, $value)
    {
        static::$registry[$key] = $value;
    }

    public static function get($key)
    {
        if (!array_key_exists($key, self::$registry)) {
            throw new Exception("No {$key} is bound in the container.");
        }
        return self::$registry[$key];
    }

    public static function __callStatic($key, $arguments)
    {
        if (! array_key_exists(strtolower($key), self::$registry)) {
            throw new Exception("No {$key} is bound in the container.");
        }
        return self::$registry[strtolower($key)];
    }
}
