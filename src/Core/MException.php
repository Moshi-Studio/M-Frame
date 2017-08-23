<?php

namespace Core;

use Exception;

class MException extends Exception
{

    public static function raise($message = "")
    {
        throw new static($message);
    }
    
    public static function report(Exception $exception)
    {
        $class = get_class($exception);
        $message = $exception->getMessage();
        $code = $exception->getCode();
        $file = $exception->getFile();
        $line = $exception->getLine();
        $trace = $exception->getTraceAsString();
        $date = date('M d Y G:iA');
        $logMessage = "Report information:\n\tClass: {$class}\n\tDate: {$date}\n\tMessage: {$message}\n\tCode: {$code}\n\tFile: {$file}\n\tLine: {$line}\n\tStack trace:\n{$trace}\n\n";
        error_log($logMessage, 3, __DIR__ . '/../../logs/error.log');
    }
}