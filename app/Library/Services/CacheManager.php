<?php

namespace App\Library\Services;

use App\Library\Utilities\Config;
use App\Library\Interfaces\CacheInterface;
use Exception;

class CacheManager
{
    private $cacheInstance;
    private $cacheType;

    /**
     * 构造函数，支持传入缓存类型
     *
     * @param string|null $cacheType 缓存类型 (默认为 null，表示使用配置文件中的值)
     */
    public function __construct($cacheType = null)
    {
        // 获取缓存配置
        $cacheConfig = Config::get('Cache');
        $systemConfig = Config::get('System');

        // 如果传入了缓存类型，则使用传入的类型；否则使用配置文件中的默认缓存类型
        $this->cacheType = $cacheType ?? ($systemConfig['cache'] ?? 'file');

        try {
            // 动态实例化缓存类
            $this->initializeCache($cacheConfig[$this->cacheType]);
        } catch (Exception $e) {
            // 捕获异常并提供友好的错误提示
            echo $e->getMessage();
            exit;  // 停止执行
        }
    }

    /**
     * 动态实例化缓存类
     *
     * @param array $cacheConfig 缓存配置项
     * @throws Exception
     */
    private function initializeCache(array $cacheConfig)
    {
        if (isset($cacheConfig['ReflectionPath'])) {
            $cacheClass = $cacheConfig['ReflectionPath'];
            // 动态实例化缓存类
            $this->cacheInstance = new $cacheClass($cacheConfig);
        } else {
            throw new Exception("Cache configuration does not contain ReflectionPath.");
        }
    }

    /**
     * 获取缓存实例
     *
     * @return CacheInterface 缓存实例
     */
    public function getCacheInstance()
    {
        return $this->cacheInstance;
    }

    /**
     * 设置缓存
     *
     * @param string $key 缓存键
     * @param mixed $value 缓存值
     * @param int $ttl 缓存过期时间（秒）
     * @return bool
     */
    public function set($key, $value, $ttl = 3600)
    {
        return $this->cacheInstance->set($key, $value, $ttl);
    }

    /**
     * 获取缓存
     *
     * @param string $key 缓存键
     * @return mixed
     */
    public function get($key)
    {
        return $this->cacheInstance->get($key);
    }

    /**
     * 删除缓存
     *
     * @param string $key 缓存键
     * @return bool
     */
    public function delete($key)
    {
        return $this->cacheInstance->delete($key);
    }

    /**
     * 清除所有缓存
     *
     * @return bool
     */
    public function clear()
    {
        return $this->cacheInstance->clear();
    }
}
