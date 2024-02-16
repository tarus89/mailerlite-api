<?php
require 'env_loader.php';

class RedisCache implements CacheInterface
{
    private $redis;

    public function __construct()
    {
        $this->redis = new \Predis\Client([
            'host' => $_ENV['REDIS_HOST'],
            'port' => $_ENV['REDIS_PORT'],
        ]);
    }

    public function get($key)
    {
        return $this->redis->get($key);
    }

    public function setex($key, $ttl, $value)
    {
        $this->redis->setex($key, $ttl, $value);
    }

    public function del($key)
    {
        $this->redis->del($key);
    }
}
