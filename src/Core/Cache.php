<?php
namespace Core;

use Memcached;

class Cache extends Memcached
{
    private static $instance;
    private static $host;
    private static $port;
    private static $compress;
    private static $expiry;

    public function get($key)
    {
        return parent::get($key);
    }

    public function set($key = null, $value = null, $ttl = null)
    {
        $expiry = ($ttl === null) ? self::$expiry : $ttl;
        return parent::set($key, $value, $expiry);
    }

    public function delete($key)
    {
        return parent::delete($key);
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
        }
        return self::$instance;
    }
}
