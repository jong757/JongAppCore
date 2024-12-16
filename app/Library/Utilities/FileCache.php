<?php 
namespace App\Library\Utilities;

use App\Library\Interfaces\CacheInterface;
use Exception;
use App\Library\Traits\DirectoryHelper; // 引入 DirectoryHelper Trait

class FileCache implements CacheInterface
{
    use DirectoryHelper; // 使用 DirectoryHelper Trait

    private $cacheDir;

    public function __construct(array $config)
    {
        $this->cacheDir = PATH . ($config['cache_dir'] ?: 'cache') . DIRECTORY_SEPARATOR;
        if (!$this->dirExists($this->cacheDir)) {
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
        if (!$this->fileExists($filePath)) {
            return null;
        }

        $data = unserialize(file_get_contents($filePath));
        if ($data['expires_at'] < time()) {
            $this->fileDelete($filePath); // 删除过期缓存
            return null;
        }

        return $data['value'];
    }

    public function delete($key)
    {
        $filePath = $this->getCacheFilePath($key);

        // 如果是目录，删除目录
        if ($this->dirExists($filePath)) {
            return $this->dirDelete($filePath);
        }

        // 如果是文件，删除文件
        return $this->fileDelete($filePath);
    }

    public function clear()
    {
        $files = glob($this->cacheDir . '*');
        foreach ($files as $file) {
            // 检查是文件还是目录
            if ($this->dirExists($file)) {
                $this->dirDelete($file); // 递归删除目录
            } else {
                $this->fileDelete($file); // 删除文件
            }
        }
        return true;
    }

    /**
     * 获取缓存文件的完整路径
     *
     * @param string $key
     * @return string
     */
    private function getCacheFilePath($key)
    {
        $path = $this->cacheDir . str_replace('/', DIRECTORY_SEPARATOR, $key) . '.cache';
        $dir = dirname($path);

        // 自动创建目录
        if (!$this->dirExists($dir)) {
            $this->dirCreate($dir, 0777); // 创建目录
        }

        return $path;
    }
}
