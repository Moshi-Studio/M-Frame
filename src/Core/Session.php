<?php

namespace Core;

use Memcached;

class Session
{
    const COOKIE = 'Session';

    private $key;
    private $host;
    private $port;
    private $store;
    private $expiry;
    private $instance;
    private $namespace;

    public function __construct($host, $port, $expire, $namespace = null)
    {
        $this->host = $host;
        $this->port = $port;
        $this->expiry = $expire;
        $this->namespace = is_null($namespace) ? '' : $namespace . '_';
        $this->instance = new Memcached();
        $this->instance->addServer($this->host, $this->port);

        if (empty($_COOKIE[self::COOKIE])) {
            $key = md5(uniqid(rand(), true));
            setcookie(self::COOKIE, $key, time() + 60 * 60 * 24 * 30, '/');
            $_COOKIE[self::COOKIE] = $key;
        }

        $this->key = $_COOKIE[self::COOKIE];
        $this->store = $this->getAll();
    }

    public function get($key)
    {
        if (!isset($this->store[$key])) {
            return false;
        }
        return $this->store[$key];
    }

    private function getAll()
    {
        return $this->instance->get($this->namespace . $this->key);
    }

    public function set($key, $value)
    {
        $this->store[$key] = $value;
        return $this->instance->set($this->namespace . $this->key, $this->store, $this->expiry);
    }

    public function delete($key)
    {
        unset($this->store[$key]);
        return $this->instance->set($this->namespace . $this->key, $this->store, $this->expiry);
    }

    public function end()
    {
        $this->store = null;
        $this->instance->delete($this->namespace . $this->key);
        setcookie(self::COOKIE, null, time() - 60 * 60 * 24);
    }
}
