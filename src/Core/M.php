<?php

namespace Core;

/**
 * @method static M Cache()
 * @method static M Database()
 * @method static M Session()
 */
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
            throw new \Exception("No {$key} is bound in the container.");
        }
        return self::$registry[$key];
    }

    public static function __callStatic($key, $arguments)
    {
        if (!array_key_exists(strtolower($key), self::$registry)) {
            throw new \Exception("No {$key} is bound in the container.");
        }
        return self::$registry[strtolower($key)];
    }

    public static function errorHandler($e, $customMessage = "")
    {
        if (!$e instanceof \Exception) {
            $class = get_class($e);
            $message = $e->getMessage();
            $code = $e->getCode();
            $file = $e->getFile();
            $line = $e->getLine();
            $trace = $e->getTraceAsString();
            $date = date('M d Y G:iA');

            $logMessage = "PHP Error information: {$customMessage}\n\tClass: {$class}\n\tDate: {$date}\n\tMessage: {$message}\n\tCode: {$code}\n\tFile: {$file}\n\tLine: {$line}\n\tStack trace:\n{$trace}\n\n";

            error_log($logMessage, 3, BASE_PATH . '/logs/error.log');
        }
    }
}
