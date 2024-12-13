<?php
namespace App\Library\Services;

use App\Library\Interfaces\CacheInterface;
use App\Library\Utilities\Config;
use Exception;

class CacheManager
{
    private static $_instances = [];
    private $cacheInstance;

    private function __construct($cacheType)
    {
        $cacheConfig = Config::get('Cache');
        $systemConfig = Config::get('System');

        // 如果传入了缓存类型，则使用传入的类型；否则使用配置文件中的默认缓存类型
        $cacheType = $cacheType ?? ($systemConfig['cache'] ?? 'file');

        try {
            $this->initializeCache($cacheType, $cacheConfig);
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;  // 停止执行
        }
    }

    /**
     * 获取缓存实例
     *
     * @param string $cacheType 缓存类型
     * @param bool $refresh 是否刷新实例
     * @return CacheManager
     */
    public static function instance($cacheType = null, $refresh = false)
    {
        // 使用缓存类型作为实例标识，确保每个缓存类型只有一个实例
        $cacheType = $cacheType ?? 'file';

        if ($refresh || !isset(self::$_instances[$cacheType])) {
            self::$_instances[$cacheType] = new self($cacheType);
        }

        return self::$_instances[$cacheType];
    }

    /**
     * 根据缓存类型初始化对应的缓存实例
     *
     * @param string $cacheType 缓存类型
     * @param array $cacheConfig 缓存配置
     * @throws Exception
     */
    private function initializeCache($cacheType, array $cacheConfig)
    {
        // 缓存类型与类的映射关系
        $cacheMap = [
            'redis' => \App\Library\Utilities\RedisCache::class,
            'file' => \App\Library\Utilities\FileCache::class,
            // 可以继续扩展其他缓存类型
        ];

        // 检查是否有对应的缓存类型
        if (!isset($cacheMap[$cacheType])) {
            throw new Exception("Cache type '{$cacheType}' is not supported.");
        }

        // 动态实例化缓存类
        $cacheClass = $cacheMap[$cacheType];
        $this->cacheInstance = new $cacheClass($cacheConfig[$cacheType]);
    }

    /**
     * 获取缓存实例
     *
     * @return CacheInterface
     */
    public function getCacheInstance(): CacheInterface
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
    public function set($key, $value, $ttl = 3600): bool
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
    public function delete($key): bool
    {
        return $this->cacheInstance->delete($key);
    }

    /**
     * 清除所有缓存
     *
     * @return bool
     */
    public function clear(): bool
    {
        return $this->cacheInstance->clear();
    }
}
