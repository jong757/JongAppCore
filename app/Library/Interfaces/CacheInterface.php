<?php
namespace App\Library\Interfaces;

interface CacheInterface
{
    public function set($key, $value, $expiration = 3600);
    public function get($key);
    public function delete($key);
    public function clear();
}
