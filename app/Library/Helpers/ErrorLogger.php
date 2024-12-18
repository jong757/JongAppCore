<?php

namespace App\Library\Helpers;

use App\Library\Traits\DirectoryHelper;
use App\Library\Utilities\Config;

class ErrorLogger
{
    // 使用 DirectoryHelper 处理目录操作
    use DirectoryHelper;

    /**
     * 初始化错误日志设置，并注册全局错误和异常处理器
     * @param string $logFilePath 错误日志文件路径
     */
    public static function init($logFilePath = null)
    {
        $systemConfig = Config::get('System', 'debug');
		$onoff = $systemConfig ? 'Off' : 'On'; // 'On'，'Off'

		// 反转配置：当 `systemConfig` 为 1 时，`display_errors` 为 'Off'，`log_errors` 为 'On'
		ini_set('display_errors', $onoff === 'On' ? 'Off' : 'On');  // 页面上显示错误
		ini_set('log_errors', $onoff === 'On' ? 'On' : 'Off');      // 错误日志记录
		ini_set('error_log', $logFilePath ?? __DIR__ . '/../../logs/php_error.log'); // 设置日志文件路径
        error_reporting(E_ALL);  // 显示所有错误

        // 确保日志目录存在
        $logDirectory = dirname(ini_get('error_log'));
        if (!is_dir($logDirectory)) {
            (new self)->dirCreate($logDirectory, 0777);
        }

        // 注册全局错误处理
        set_error_handler([self::class, 'handle']);

        // 注册全局异常处理
        set_exception_handler([self::class, 'handle']);
    }

    /**
     * 记录错误到日志文件
     * @param string $errorMessage 错误信息
     * @param string $context 错误发生的上下文（可选）
     */
    public static function logError($errorMessage, $context = '')
    {
        // 检查 log_errors 是否开启
        if (ini_get('log_errors') === 'On') {
            // 获取当前时间戳
            $timestamp = date('Y-m-d H:i:s');

            // 构建日志内容
            $logMessage = "[$timestamp] ERROR: $errorMessage";
            if ($context) {
                $logMessage .= " | Context: $context";
            }

            // 将错误信息写入日志文件
            error_log($logMessage);
        }
    }

    /**
     * 通用的错误和异常处理函数
     * 区分处理错误和异常
     */
    public static function handle($errorOrException)
    {
        if ($errorOrException instanceof \Exception) {
            // 处理异常
            $errorMessage = "Uncaught Exception: " . $errorOrException->getMessage() . " in " . $errorOrException->getFile() . " on line " . $errorOrException->getLine();
        } elseif (is_array($errorOrException)) {
            // 处理错误
            list($severity, $message, $file, $line) = $errorOrException;
            $errorMessage = "Error [$severity]: $message in $file on line $line";
        } else {
            // 处理未知情况
            $errorMessage = "Unknown error occurred";
        }
    
        // 记录错误日志
        self::logError($errorMessage);
    
        // 输出详细的错误信息到页面
        if (ini_get('display_errors') === 'On') {
            echo "Error: $errorMessage";  // 这里显示详细错误信息
        } else {
            echo "An unexpected error occurred. Please check the log.";  // 只在没有开启显示错误时执行
        }
    }

}
