<?php
/**
 * annotation
 * 常量加载中间件
 *
 *\App\Library\Middleware\ConstantLoader::loadModuleConstants('ModuleA');
 */

namespace App\Library\Middleware;

class ConstantLoader
{
    /**
     * 加载系统常量
     */
    public static function loadSystemConstants()
    {
        // 系统常量文件位于 App\Config\constants.php
        self::loadConstantsFromNamespace('App\\Config\\constants');
    }

    /**
     * 根据命名空间加载常量文件
     *
     * @param string $namespace 命名空间
     */
    private static function loadConstantsFromNamespace($namespace)
    {
        // 加载类时常量文件与命名空间匹配
        $class = $namespace;

        // 如果常量文件已经加载过，不重复加载
        if (!defined("{$namespace}_LOADED") && class_exists($class)) {
            // 定义常量标识符，避免重复加载
            define("{$namespace}_LOADED", true);
            // 执行类中的常量定义
            new $class();  // 实例化类触发常量定义
        }
    }

    /**
     * 加载模块常量
     *
     * @param string $module 模块名
     */
    public static function loadModuleConstants($module)
    {
        $namespace = "App\\Models\\{$module}\\constants";
        self::loadConstantsFromNamespace($namespace);
    }
}
