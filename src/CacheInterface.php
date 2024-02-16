<?php

interface CacheInterface
{
    public function get($key);

    public function setex($key, $ttl, $value);

    public function del($key);
}