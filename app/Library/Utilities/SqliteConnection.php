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
        // 检查 SQLite 驱动是否安装
        $this->checkSqliteDriver();

        $dsn = "sqlite:{$config['database']}";
        $this->pdo = new PDO($dsn);
    }

    /**
     * 检查是否安装了 SQLite PDO 驱动
     *
     * @throws \Exception 如果找不到 PDO 驱动
     */
    private function checkSqliteDriver()
    {
        if (!extension_loaded('pdo_sqlite')) {
            throw new \Exception("SQLite PDO driver is not installed. Please install the pdo_sqlite extension.");
        }
    }

    // 直接访问 PDO 实例
    public function getConnection()
    {
        return $this->pdo;
    }
}

