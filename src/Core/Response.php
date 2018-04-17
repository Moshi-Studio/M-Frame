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
            case 'api':
                self::api($data['response']);
                break;
            case 'head':
                ob_end_clean();
                break;
        }
    }

    public static function page($data, $template = 'index.php')
    {
        extract($data);
        require BASE_PATH . (!empty(SPACE) ? 'src/App/' . SPACE . '/View/' : 'src/App/View/') . $template;
        exit();
    }

    public static function api($data)
    {
        $encoded = json_encode($data);
        header('Content-Type: application/json');
        header('Content-Length:' . strlen($encoded));
        exit($encoded);
    }
}