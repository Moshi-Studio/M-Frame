<?php

namespace Core;

class Response
{
    private static $instance;

    private $responseKind = array('page', 'redirect');

    private function display($template, $vars)
    {
        if (is_array($vars)) {
            extract($vars);
        }
        $templateInclude = M::App()->getPath('view') . $template;
        if (is_file($templateInclude)) {
            require $templateInclude;
        } elseif (is_file($template)) {
            require $template;
        }
    }

    private function json($response)
    {
        $response = json_encode($response);
        header('Content-Type: application/json');
        header('Content-Length:' . strlen($response));
        echo $response;
    }

    private function redirect($url, $code = null, $offDomain = false)
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

    public function handler($response)
    {
        if (isset($response['kind']) && in_array($response['kind'], $this->responseKind)) {
            switch ($response['kind']) {
                case 'page':
                    $this->display('index.php', $response);
                    break;
                case 'redirect':
                    $this->redirect($response['url'], $response['code'], true);
                    break;
            }
        } else {
            $this->json($response);
        }
    }

    public static function getInstance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }
        return self::$instance;
    }
}