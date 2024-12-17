<?php

namespace App\Library\Utilities;

use PDO;
use App\Library\Traits\DatabaseOperations;  // 引入 DatabaseOperations trait

class SqliteConnection
{
    use DatabaseOperations;  // 使用 DatabaseOperations trait

    private $pdo;

    public function __construct(array $config)
    {
        $dsn = "sqlite:{$config['database']}";
        $this->pdo = new PDO($dsn);
    }

    // 直接访问 PDO 实例
    public function getConnection()
    {
        return $this->pdo;
    }
}
