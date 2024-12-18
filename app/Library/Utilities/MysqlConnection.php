<?php
namespace App\Library\Utilities;

use PDO;
use App\Library\Traits\DatabaseOperations;  // 引入 DatabaseOperations trait

class MysqlConnection
{
    use DatabaseOperations;  // 使用 DatabaseOperations trait

    private $pdo;

    public function __construct(array $config)
    {
        // 检查 MySQL 驱动是否安装
        $this->checkMysqlDriver();

        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
        $this->pdo = new PDO($dsn, $config['username'], $config['password']);
    }

    /**
     * 检查是否安装了 MySQL PDO 驱动
     *
     * @throws \Exception 如果找不到 PDO 驱动
     */
    private function checkMysqlDriver()
    {
        if (!extension_loaded('pdo_mysql')) {
            throw new \Exception("MySQL PDO driver is not installed. Please install the pdo_mysql extension.");
        }
    }

    // 直接访问 PDO 实例
    public function getConnection()
    {
        return $this->pdo;
    }
}
