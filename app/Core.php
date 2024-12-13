<?php
namespace App;

use Exception;

class Core
{
    // 类文件路径缓存（保存在内存中）
    private static $classMap = [];
	
	// 已加载的文件列表
	private static $loadedFiles = [];

    // 命名空间前缀与基目录的映射关系
    private static $prefixes = [
        'App' => APP_PATH,
        // 可以继续添加其他命名空间映射
    ];

    // 初始化
    public function __construct()
    {
        // 加载 Composer 自动加载器
        $this->loadComposerAutoloader();

        // 加载系统常量和函数
        $this->loadSystemConstantsAndFunctions();
    }

    /**
     * 加载 Composer 自动加载器
     */
    private function loadComposerAutoloader()
    {
        // 判断是否存在 vendor/autoload.php 文件，如果存在则加载 Composer 的自动加载器
        $composerAutoload = APP_PATH . 'Composer'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';
        if (file_exists($composerAutoload)) {
            require_once $composerAutoload;
        } else {
            throw new Exception("Composer autoloader not found. Please run 'composer install'.");
        }
    }

    /**
     * 加载系统常量和函数
     */
    private function loadSystemConstantsAndFunctions()
    {
        // 加载常量
        \App\Library\Middleware\ConstantLoader::loadSystemConstants();

        // 加载系统函数
        \App\Library\Middleware\FunctionLoader::loadSystemFunctions();
    }

    /**
     * 注册自动加载器
     */
    public static function register()
    {
        spl_autoload_register([__CLASS__, 'autoload']);
    }

    /**
     * 自动加载方法
     *
     * @param string $class 类名
     */
    public static function autoload($class)
    {
        try {
            // 如果类路径已经缓存，直接加载
            if (isset(self::$classMap[$class])) {
                require self::$classMap[$class];
                return;
            }

            // 遍历命名空间前缀映射
            foreach (self::$prefixes as $prefix => $baseDir) {
                $len = strlen($prefix);
                if (strncmp($prefix, $class, $len) !== 0) {
                    continue;
                }

                // 获取相对类名
                $relativeClass = substr($class, $len);

                // 修正路径拼接问题，确保路径正确
                $baseDir = rtrim($baseDir, DIRECTORY_SEPARATOR); // 去除 baseDir 末尾的斜杠
                $file = $baseDir . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';

                // 如果文件存在，则引入文件并缓存路径
                if (file_exists($file)) {
                    self::$classMap[$class] = $file; // 缓存类路径
					self::$loadedFiles[] = $file;    // 记录已加载的文件
                    require $file;
                    return;
                }
            }

            // 如果文件不存在，抛出异常
            throw new Exception("Class $class not found.");
        } catch (Exception $e) {
            // 处理异常，记录错误日志
            error_log($e->getMessage());
            // 显示错误信息
            echo "Error: " . $e->getMessage();
        }
    }

    /**
     * 获取类映射（如果需要从内存中访问类路径）
     *
     * @return array 类路径映射
     */
    public static function getClassMap()
    {
        return self::$classMap;
    }
	
	/**
	 * 获取已经加载的文件映射
	 *
	 * @return array 已加载的文件路径
	 */
	public static function getLoadedFiles()
	{
		return self::$loadedFiles;
	}
	
}
