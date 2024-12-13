<?php
namespace App\Library\Traits;

trait CacheTrait
{
    public static function getCacheFilePath($key, $cacheDir)
    {
        return $cacheDir . md5($key) . '.cache';
    }

    public static function saveCacheToFile($file, $data, $expiration)
    {
        $content = serialize([
            'data' => $data,
            'expiration' => time() + $expiration
        ]);
        return file_put_contents($file, $content);
    }

    public static function getCacheFromFile($file)
    {
        $content = file_get_contents($file);
        $cache = unserialize($content);
        if (time() > $cache['expiration']) {
            return null;
        }
        return $cache['data'];
    }

    public static function isCacheExpired($file)
    {
        if (!file_exists($file)) {
            return true;
        }
        $content = file_get_contents($file);
        $cache = unserialize($content);
        return time() > $cache['expiration'];
    }

    public static function deleteCacheFile($file)
    {
        return unlink($file);
    }
}
