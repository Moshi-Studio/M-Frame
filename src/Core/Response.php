<?php

namespace Core;

class Response
{
    public static function handler($data)
    {
        if (!empty($data['response']['code'])) {
            http_response_code($data['response']['code']);
        }

        switch ($data['type']) {
            case 'page':
                self::page($data['response']);
                break;
            case 'json':
                self::json($data['response']);
                break;
            case 'jsonp':
                self::jsonp($data['response']);
                break;
            case 'head':
                ob_end_clean();
                break;
        }
    }

    public static function page($data)
    {
        extract($data);
        require BASE_PATH . (!empty(SPACE) ? 'src/App/' . SPACE . '/View/' : 'src/App/View/') . 'index.php';
        exit();
    }

    public static function json($data)
    {
        $encoded = json_encode($data);
        header('Content-Type: application/json');
        header('Content-Length:' . strlen($encoded));
        exit($encoded);
    }

    public static function jsonp($data)
    {
        $body = $_GET['callback'] . '(' . json_encode($data) . ')';
        header("Content-Type: application/json");
        header('Content-Length:' . strlen($body));
        exit($body);
    }
}