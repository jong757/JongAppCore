<?php
//目前只有读取
namespace App\Library\Utilities;

use App\Library\Interfaces\ConfigInterface;
use Exception;

class Config implements ConfigInterface
{
    private static $config = [];
    private static $directory = 'Config'; // 默认配置目录

    /**
     * 设置自定义配置目录
     *
     * @param string $dir 自定义目录
     */
    public static function setDirectory($dir)
    {
        self::$directory = $dir;
    }

    /**
     * 获取配置项
     *
     * @param string $namespace 配置命名空间
     * @param string|null $key 配置键，支持嵌套键（如 redis.host）
     * @return mixed 配置值
     */
    public static function get($namespace, $key = null)
    {
        if (!isset(self::$config[$namespace])) {
            self::load($namespace);
        }

        if ($key !== null) {
            return self::getNestedConfig(self::$config[$namespace], $key);
        }

        return self::$config[$namespace] ?? null;
    }

    private static function getNestedConfig(array $config, $key)
    {
        if (strpos($key, '.') === false) {
            return $config[$key] ?? null;
        }

        $keys = explode('.', $key);
        $currentKey = array_shift($keys);

        if (isset($config[$currentKey])) {
            return self::getNestedConfig($config[$currentKey], implode('.', $keys));
        }

        return null;
    }

    /**
     * 设置或修改配置项
     *
     * @param string $namespace 配置命名空间
     * @param string $key 配置键
     * @param mixed $value 配置值
     */
    public static function set($namespace, $key, $value)
    {
        if (!isset(self::$config[$namespace])) {
            self::load($namespace);
        }
    
        // 修改内部配置项
        self::setNestedConfig(self::$config[$namespace], $key, $value);
    
        // 调用修改后的 save 方法，只保存特定配置项
        self::save($namespace, $key, $value);
    }


    private static function setNestedConfig(array &$config, $key, $value)
    {
        if (strpos($key, '.') === false) {
            $config[$key] = $value;
            return;
        }

        $keys = explode('.', $key);
        $currentKey = array_shift($keys);

        if (!isset($config[$currentKey])) {
            $config[$currentKey] = [];
        }

        self::setNestedConfig($config[$currentKey], implode('.', $keys), $value);
    }

    /**
     * 删除配置项
     *
     * @param string $namespace 配置命名空间
     * @param string|null $key 配置键，如果为空则删除整个命名空间
     */
    public static function delete($namespace, $key = null)
    {
        if (!isset(self::$config[$namespace])) {
            self::load($namespace);
        }

        if ($key === null) {
            unset(self::$config[$namespace]);
        } else {
            self::deleteNestedConfig(self::$config[$namespace], $key);
        }

        self::save($namespace);
    }

    private static function deleteNestedConfig(array &$config, $key)
    {
        if (strpos($key, '.') === false) {
            unset($config[$key]);
            return;
        }

        $keys = explode('.', $key);
        $currentKey = array_shift($keys);

        if (isset($config[$currentKey])) {
            self::deleteNestedConfig($config[$currentKey], implode('.', $keys));

            if (empty($config[$currentKey])) {
                unset($config[$currentKey]);
            }
        }
    }

    /**
     * 加载配置文件
     *
     * @param string $namespace 配置命名空间
     */
    public static function load($namespace)
    {
        $file = APP_PATH . self::$directory . DIRECTORY_SEPARATOR . $namespace . '.php';
        if (file_exists($file)) {
            self::$config[$namespace] = include $file;
        } else {
            throw new Exception("Config file for {$namespace} not found in " . self::$directory . " directory.");
        }
    }

    /**
     * 保存配置到文件
     *
     * @param string $namespace 配置命名空间
     */
    public static function save($namespace)
    {
        $file = APP_PATH . self::$directory . DIRECTORY_SEPARATOR . $namespace . '.php';
        if (file_exists($file)) {
            // 将双反斜杠替换为单反斜杠
            $configContent = "<?php\nreturn " . self::convertArraySyntax(var_export(self::$config[$namespace], true)) . ";\n";
            // 替换双反斜杠为单反斜杠
            $configContent = str_replace('\\\\', '\\', $configContent);

            file_put_contents($file, $configContent);
        } else {
            throw new Exception("Config file for {$namespace} not found in " . self::$directory . " directory.");
        }
    }


    /**
     * 将 array() 转换为短数组语法 []
     *
     * @param string $str
     * @return string
     */
    private static function convertArraySyntax($str)
    {
        return str_replace('array (', '[', str_replace(')', ']', $str));
    }
}
