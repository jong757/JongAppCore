<?php

namespace App\Library\Services;

use App\Library\Utilities\Config;
use Exception;

class DatabaseService
{
    private $dbConnection;
    private $dbType;

    /**
     * 构造函数，支持传入数据库类型
     *
     * @param string|null $dbType 数据库类型 (默认为 null，表示使用配置文件中的值)
     */
    public function __construct($dbType = null)
    {
        // 获取数据库配置
        $dbConfig = Config::get('Database');
        $systemConfig = Config::get('System');

        // 如果传入了数据库类型，则使用传入的类型；否则使用配置文件中的默认数据库类型
        $this->dbType = $dbType ?? ($systemConfig['db'] ?? 'mysql');
		
        try {
            // 动态实例化数据库连接类
            $this->initializeDatabase($dbConfig[$this->dbType]);
        } catch (Exception $e) {
            // 捕获异常并提供友好的错误提示
            echo $e->getMessage();
            exit;  // 停止执行
        }
    }

    /**
     * 动态实例化数据库连接类
     *
     * @param array $dbConfig 数据库配置项
     * @throws Exception
     */
    private function initializeDatabase(array $dbConfig)
    {
        if (isset($dbConfig['ReflectionPath'])) {
            $dbClass = $dbConfig['ReflectionPath'];  // 获取配置中的 ReflectionPath

            if (class_exists($dbClass)) {
                // 动态实例化数据库连接类
                $this->dbConnection = new $dbClass($dbConfig);
            } else {
                throw new Exception("Database connection class {$dbClass} not found.");
            }
        } else {
            throw new Exception("Database configuration does not contain ReflectionPath.");
        }
    }

    /**
     * 获取数据库连接实例
     *
     * @return mixed
     */
    public function getConnection()
    {
        return $this->dbConnection;
    }
}
