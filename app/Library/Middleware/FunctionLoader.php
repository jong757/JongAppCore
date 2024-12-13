<?php
/**
 * 函数加载中间件
 *
 * 加载系统函数
 * \App\Library\Middleware\FunctionLoader::loadSystemFunction();
 * 加载 Blog 模块函数
 * \App\Library\Middleware\FunctionLoader::loadModuleFunction('Blog');
 */

namespace App\Library\Middleware;

class FunctionLoader
{
    /**
     * 加载系统函数
     */
    public static function loadSystemFunctions()
    {
        // 系统函数文件位于 App\Helpers\Function.php
        self::loadFunctionsFromNamespace('App\\Library\\Helpers\\Function');
    }

    /**
     * 根据命名空间加载函数文件
     *
     * @param string $namespace 命名空间
     */
    private static function loadFunctionsFromNamespace($namespace)
    {
        // 获取函数文件路径
        $file = self::getFunctionsFilePath($namespace);
        
        // 调试输出，确保文件路径是正确的
        

        // 如果函数文件尚未加载过，且文件存在，则加载
        if (file_exists($file) && !defined("{$namespace}_LOADED")) {
            // 防止重复加载
            define("{$namespace}_LOADED", true);
            // 加载函数文件
            require_once $file;
        }
    }

    /**
     * 获取函数文件的实际路径
     *
     * @param string $namespace 命名空间
     * @return string 返回函数文件路径
     */
	private static function getFunctionsFilePath($namespace)
	{
		// 拼接完整路径，确保包含 app 目录
		$namespacePath = str_replace(['\\','App\\'], [DIRECTORY_SEPARATOR,''], $namespace);
		// 确保路径拼接正确
		$file = APP_PATH . $namespacePath . '.php';
		return $file;
	}

    /**
     * 加载模块函数
     *
     * @param string $module 模块名
     */
    public static function loadModuleFunction($module)
    {
        // 加载指定模块的函数
        $namespace = "App\\Models\\{$module}\\Functions";
        self::loadFunctionsFromNamespace($namespace);
    }
}
