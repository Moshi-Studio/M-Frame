<?php

namespace Core;

use Memcached;

class Cache
{
    private $host;
    private $port;
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
    }

    public function get($key)
    {
        return $this->instance->get($this->namespace . $key);
    }

    public function set($key, $value, $ttl = null)
    {
        $expiry = is_null($ttl) ? $this->expiry : $ttl;
        return $this->instance->set($this->namespace . $key, $value, $expiry);
    }

    public function delete($key)
    {
        return $this->instance->delete($this->namespace . $key);
    }

    public function getStats()
    {
        return $this->instance->getStats()[$this->host . ':' . $this->port];
    }
}
