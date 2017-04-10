<?php

namespace Src\Core;

use Memcached;

class Session extends Memcached
{
    const COOKIE = 'Session';

    private static $instance;
    private static $host;
    private static $port;
    private static $compress;
    private static $expiry;

    private $key;
    private $store;

    public function get($key)
    {
        return $this->store[$key];
    }

    public function set($key, $value)
    {
        $this->store[$key] = $value;
        parent::set($this->key, $this->store, self::$expiry);
        return $value;
    }

    public function end()
    {
        parent::delete($this->key);
        $this->store = null;
        setcookie(self::COOKIE, null, time() - 86400);
    }

    private function getAll()
    {
        return parent::get($this->key);
    }

    public static function employ($host, $port, $compress, $expiry)
    {
        self::$host = $host;
        self::$port = (int) $port;
        self::$compress = (int) $compress;
        self::$expiry = (int) $expiry;
    }

    public static function getInstance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
            self::$instance->addServer(self::$host, self::$port);
            if (empty($_COOKIE[Session::COOKIE])) {
                $key = md5(uniqid(rand(), true));
                setcookie(Session::COOKIE, $key, time() + 1209600, '/');
                $_COOKIE[Session::COOKIE] = $key;
            }
            self::$instance->key = $_COOKIE[Session::COOKIE];
            self::$instance->store = self::$instance->getAll();
        }
        return self::$instance;
    }
}
