<?php

namespace Core;

class Response
{
    public static function handler($data)
    {
        switch ($data['type']) {
            case 'page':
                if (!empty($data['response']['code'])) {
                    self::page($data['response'], $data['response']['code'], 'index.php');
                }
                self::page($data['response']);
                break;
            case 'api':
                if (!empty($data['response']['code'])) {
                    self::api($data['response'], $data['response']['code']);
                }
                self::api($data['response']);
                break;
        }
    }

    public static function page($data, $code = NULL, $template = 'index.php')
    {
        if (!is_null($code)) {
            http_response_code($code);
        }
        extract($data);
        require BASE_PATH . (!empty(SPACE) ? 'src/App/' . SPACE . '/View/' : 'src/App/View/') . $template;
        exit();
    }

    public static function api($data, $code = NULL)
    {
        if (!is_null($code)) {
            http_response_code($code);
        }
        $encoded = json_encode($data);
        header('Content-Type: application/json');
        header('Content-Length:' . strlen($encoded));
        exit($encoded);
    }
}