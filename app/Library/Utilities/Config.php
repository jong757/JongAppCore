<?php


namespace App\Library\Utilities;

use App\Library\Interfaces\ConfigInterface;
use Exception;

class Config implements ConfigInterface
{
    private static $config = [];

    /**
     * 获取配置项
     *
     * @param string $namespace 配置命名空间
     * @param string|null $key 配置键
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
     * 添加或修改配置项
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

        self::setNestedConfig(self::$config[$namespace], $key, $value);
        self::save($namespace);
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
        $file = APP_PATH . 'Config' . DIRECTORY_SEPARATOR . $namespace . '.php';
        if (file_exists($file)) {
            self::$config[$namespace] = include $file;
        } else {
            throw new Exception("Config file for {$namespace} not found.");
        }
    }

    /**
     * 保存配置到文件
     *
     * @param string $namespace 配置命名空间
     */
    public static function save($namespace)
    {
        $file = APP_PATH . 'Config' . DIRECTORY_SEPARATOR . $namespace . '.php';
        if (file_exists($file)) {
            $configContent = "<?php\nreturn " . self::convertArraySyntax(var_export(self::$config[$namespace], true)) . ";\n";
            file_put_contents($file, $configContent);
        } else {
            throw new Exception("Config file for {$namespace} not found.");
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

