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
        $dsn = "sqlsrv:Server={$config['host']};Database={$config['dbname']}";
        $this->pdo = new PDO($dsn, $config['username'], $config['password']);
    }

    // 直接访问 PDO 实例
    public function getConnection()
    {
        return $this->pdo;
    }
}
