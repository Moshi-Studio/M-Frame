<?php

namespace Core;

class Response
{
    public static function handler($response)
    {
        if (isset($response['kind']) && in_array($response['kind'], array('page', 'redirect'))) {
            switch ($response['kind']) {
                case 'page':
                    self::page('index.php', $response);
                    break;
                case 'redirect':
                    self::redirect($response['url'], $response['code'], true);
                    break;
            }
        } else {
            self::json($response);
        }
    }

    private static function page($template, $vars)
    {
        if (is_array($vars)) {
            extract($vars);
        }
        $viewsPath = BASE_PATH . (($space = App::space()) ? '/src/App/' . $space . '/View/' : '/src/App/View/');
        $templateInclude = $viewsPath . $template;
        if (is_file($templateInclude)) {
            require $templateInclude;
        } elseif (is_file($template)) {
            require $template;
        }
    }

    private static function redirect($url, $code = null, $offDomain = false)
    {
        $continue = !empty($url);
        if ($offDomain === false && preg_match('#^https?://#', $url)) {
            $continue = false;
        }
        if ($continue) {
            if ($code != null && (int)$code == $code) {
                header("Status: {$code}");
            }
            header("Location: {$url}");
            die();
        }
    }

    private static function json($response)
    {
        $response = json_encode($response);
        header('Content-Type: application/json');
        header('Content-Length:' . strlen($response));
        echo $response;
    }
}