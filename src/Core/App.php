<?php

namespace Core;

use stdClass;

class App
{
    private static $instance;

    private $path;
    private $config;

    public function setEnv($env)
    {
        $name = 'app-' . $env;
        $this->config->app = $this->config->$name;
    }

    public function setPath($name, $path)
    {
        $this->path[$name] = $path;
    }

    public function getPath($name)
    {
        return isset($this->path[$name]) ? $this->path[$name] : null;
    }

    public function getConfig($name)
    {
        if (!empty($name)) {
            return isset($this->config->$name) ? $this->config->$name : null;
        } else {
            return $this->config;
        }
    }

    public function space()
    {
        if ($spaces = $this->getConfig('app')->spaces) {
            $uris = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
            $uris = str_replace($this->getPath('sp'), '/', $uris);
            $uri = strtolower(explode('/', $uris)[1]);
            $spaces = explode(',', $spaces);
            if ($uris !== '/' && in_array($uri, $spaces)) {
                return ucfirst($uri);
            }
            return 'Web';
        }
        return false;
    }

    public function init($basePath)
    {
        $this->setPath('root', $basePath);
        $this->setPath('base', $basePath . '/src/Core/');
        $this->setPath('config', $basePath . '/src/');

        $this->loadConfig();

        if ($space = $this->space()) {
            DEFINE('APP_DIR', $basePath . '/app/' . $space);
            $this->setPath('controller', APP_DIR . '/Controller/');
            $this->setPath('model', APP_DIR . '/Model/');
            $this->setPath('view', APP_DIR . '/View/');
        } else {
            DEFINE('APP_DIR', '/');
            $this->setPath('controller', $basePath . '/app/Controller/');
            $this->setPath('model', $basePath . '/app/Model/');
            $this->setPath('view', $basePath . '/app/View/');
        }
    }

    public function employ($database = null, $session = null, $cache = null, $constants = null)
    {
        if (!empty($database)) {
            Database::employ(
                $this->getConfig($database)->type,
                $this->getConfig($database)->name,
                $this->getConfig($database)->host,
                $this->getConfig($database)->username,
                $this->getConfig($database)->password,
                $this->getConfig($database)->port
            );
        }

        if (!empty($session)) {
            Session::employ(
                $this->getConfig($session)->host,
                $this->getConfig($session)->port,
                $this->getConfig($session)->compress,
                $this->getConfig($session)->expiry
            );
        }

        if (!empty($cache)) {
            Cache::employ(
                $this->getConfig($cache)->host,
                $this->getConfig($cache)->port,
                $this->getConfig($cache)->compress,
                $this->getConfig($cache)->expiry
            );
        }

        if (!empty($constants)) {
            $this->loadConstants($this->getConfig($constants));
        }
    }

    public function spacePublic($dir) {
        $this->setPath('sp', $dir);
        DEFINE('SP_DIR', $this->getPath('sp'));
        DEFINE('SP_FILES', M::App()->space() ? SP_DIR . 'assets/' . strtolower(M::App()->space()) . '/' : SP_DIR . 'assets/');
    }

    public function run()
    {
        M::Response()->handler(M::Route()->dispatch());
    }

    private function loadConfig()
    {
        $this->config = new stdClass;
        $file = $this->getPath('config') . 'Config.ini';
        $parsed_array = parse_ini_file($file, true);
        foreach ($parsed_array as $key => $value) {
            if (!is_array($value)) {
                $this->config->$key = $value;
            } else {
                if (!isset($this->config->$key)) {
                    $this->config->$key = new stdClass;
                }
                foreach ($value as $innerKey => $innerValue) {
                    $this->config->$key->$innerKey = $innerValue;
                }
            }
        }
    }

    private function loadConstants($constants)
    {
        foreach ($constants as $key => $value) {
            define(strtoupper($key), $value);
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
