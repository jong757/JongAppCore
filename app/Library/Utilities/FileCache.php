<?php
namespace App\Library\Utilities;

use App\Library\Interfaces\CacheInterface;
use Exception;

class FileCache implements CacheInterface
{
    private $cacheDir;

    public function __construct(array $config)
    {
        $this->cacheDir = PATH . ($config['cache_dir'] ?: 'cache') . DIRECTORY_SEPARATOR;
        if (!is_dir($this->cacheDir)) {
            throw new Exception("Cache directory does not exist: " . $this->cacheDir);
        }
    }

    public function initialize()
    {
        // 初始化操作，例如清理过期缓存等
    }

    public function set($key, $value, $ttl = 3600)
    {
        $filePath = $this->getCacheFilePath($key);
        $data = [
            'value' => $value,
            'expires_at' => time() + $ttl
        ];
        return file_put_contents($filePath, serialize($data));
    }

    public function get($key)
    {
        $filePath = $this->getCacheFilePath($key);
        if (!file_exists($filePath)) {
            return null;
        }

        $data = unserialize(file_get_contents($filePath));
        if ($data['expires_at'] < time()) {
            unlink($filePath); // 删除过期缓存
            return null;
        }

        return $data['value'];
    }

    public function delete($key)
    {
        $filePath = $this->getCacheFilePath($key);
        if (file_exists($filePath)) {
            unlink($filePath);
            return true;
        }
        return false;
    }

    public function clear()
    {
        $files = glob($this->cacheDir . '*');
        foreach ($files as $file) {
            unlink($file);
        }
        return true;
    }

    private function getCacheFilePath($key)
    {
        $path = $this->cacheDir . str_replace('/', DIRECTORY_SEPARATOR, $key) . '.cache';
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true); // 自动创建多层目录
        }
        return $path;
    }
}
