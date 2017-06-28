<?php

namespace Data;

use Memcached;

class Cache
{
    private $expiry;
    private $instance;

    public function __construct($host, $port, $expire)
    {
        $this->instance = new Memcached();
        $this->instance->addServer($host, (int) $port);
        $this->expiry = (int) $expire;
    }

    public function get($key)
    {
        return $this->instance->get($key);
    }

    public function set($key = null, $value = null, $ttl = null)
    {
        $expiry = ($ttl === null) ? $this->expiry : $ttl;
        return $this->instance->set($key, $value, $expiry);
    }

    public function delete($key)
    {
        return $this->instance->delete($key);
    }
}
