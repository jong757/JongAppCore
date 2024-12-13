<?php
namespace App\Library\Utilities;

use App\Library\Interfaces\CacheInterface;
use Redis;
use Exception;

class RedisCache implements CacheInterface
{
    private $redis;
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->redis = new Redis();
        $this->connect();
    }

    /**
     * 连接 Redis
     */
    private function connect()
    {
        try {
            $this->redis->connect($this->config['host'], $this->config['port']);
            if (!empty($this->config['password'])) {
                $this->redis->auth($this->config['password']);
            }
            if (isset($this->config['database'])) {
                $this->redis->select($this->config['database']);
            }
        } catch (Exception $e) {
            throw new Exception("Unable to connect to Redis: " . $e->getMessage());
        }
    }

    /**
     * 处理 Redis 缓存的键
     * 
     * @param string $key
     * @return string
     */
    private function sanitizeKey($key)
    {
        return str_replace('/', ':', $key);  // Redis 缓存使用冒号分隔符
    }

    /**
     * 设置缓存
     * 
     * @param string $key 缓存键
     * @param mixed $value 缓存值
     * @param int $ttl 缓存过期时间
     * @return bool
     */
    public function set($key, $value, $ttl = 3600)
    {
        $key = $this->sanitizeKey($key);  // 使用 sanitizeKey 处理键

        // 对数组或对象进行 JSON 编码
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value);
        }

        return $this->redis->setex($key, $ttl, $value);
    }

    /**
     * 获取缓存
     * 
     * @param string $key 缓存键
     * @return mixed
     */
    public function get($key)
    {
        $key = $this->sanitizeKey($key);  // 使用 sanitizeKey 处理键
        $value = $this->redis->get($key);

        // 如果是 JSON 格式的值，解码为数组或对象
        $decodedValue = json_decode($value, true);

        // 如果 JSON 解码成功，返回数组；否则返回原始值
        return (json_last_error() === JSON_ERROR_NONE) ? $decodedValue : $value;
    }

    /**
     * 删除缓存
     * 
     * @param string $key 缓存键
     * @return int
     */
    public function delete($key)
    {
        $key = $this->sanitizeKey($key);  // 使用 sanitizeKey 处理键
        return $this->redis->del($key);
    }

    /**
     * 清除所有缓存
     * 
     * @return bool
     */
    public function clear()
    {
        return $this->redis->flushDB();
    }
}

