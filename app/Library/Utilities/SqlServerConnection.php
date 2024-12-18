<?php

namespace App\Library\Utilities;

use PDO;
use App\Library\Traits\DatabaseOperations;  // 引入 DatabaseOperations trait

class SqlServerConnection
{
    use DatabaseOperations;  // 使用 DatabaseOperations trait

    private $pdo;

    public function __construct(array $config)
    {
        // 检查 SQL Server 驱动是否安装
        $this->checkSqlServerDriver();

        $dsn = "sqlsrv:Server={$config['host']};Database={$config['dbname']}";
        $this->pdo = new PDO($dsn, $config['username'], $config['password']);
    }

    /**
     * 检查是否安装了 SQL Server PDO 驱动
     *
     * @throws \Exception 如果找不到 PDO 驱动
     */
    private function checkSqlServerDriver()
    {
        if (!extension_loaded('pdo_sqlsrv')) {
            throw new \Exception("SQL Server PDO driver is not installed. Please install the pdo_sqlsrv extension.");
        }
    }

    // 直接访问 PDO 实例
    public function getConnection()
    {
        return $this->pdo;
    }
}

