<?php

namespace Data;

use Memcached;

class Session
{
    const COOKIE = 'Session';

    private $expiry;
    private $key;
    private $store;
    private $instance;

    public function __construct($host, $port, $expire)
    {
        $this->instance = new Memcached();
        $this->instance->addServer($host, (int) $port);
        if (empty($_COOKIE[Session::COOKIE])) {
            $key = md5(uniqid(rand(), true));
            setcookie(Session::COOKIE, $key, time() + 1209600, '/');
            $_COOKIE[Session::COOKIE] = $key;
        }
        $this->expiry = (int) $expire;
        $this->key = $_COOKIE[Session::COOKIE];
        $this->store = $this->getAll();
    }

    public function get($key)
    {
        return $this->store[$key];
    }

    public function set($key, $value)
    {
        $this->store[$key] = $value;
        $this->instance->set($this->key, $this->store, $this->expiry);
        return $value;
    }

    public function delete($key)
    {
        $value = $this->store[$key];
        unset($this->store[$key]);
        $this->instance->set($this->key, $this->store, $this->expiry);
        return $value;
    }

    public function end()
    {
        $this->instance->delete($this->key);
        $this->store = null;
        setcookie(self::COOKIE, null, time() - 86400);
    }

    private function getAll()
    {
        return $this->instance->get($this->key);
    }
}
