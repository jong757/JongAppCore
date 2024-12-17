namespace App\Library\Services;

use App\Library\Interfaces\DatabaseInterface;
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
        $systemConfig = Config::get('System');
        $this->dbType = $dbType ?? ($systemConfig['db'] ?? 'mysql');  // 默认使用 'mysql'
    }

    /**
     * 获取数据库连接，延迟初始化
     *
     * @return DatabaseInterface
     * @throws Exception
     */
    public function getConnection(): DatabaseInterface
    {
        if (!$this->dbConnection) {
            $dbConfig = Config::get('Database.' . $this->dbType);
            $this->initializeDatabase($dbConfig);
        }

        return $this->dbConnection;
    }

    /**
     * 初始化数据库连接
     *
     * @param array $dbConfig 数据库配置
     * @throws Exception
     */
    private function initializeDatabase(array $dbConfig)
    {
        if (isset($dbConfig['ReflectionPath'])) {
            $dbClass = $dbConfig['ReflectionPath'];
            // 动态实例化数据库连接类
            $this->dbConnection = new $dbClass($dbConfig);
        } else {
            throw new Exception("Database configuration does not contain ReflectionPath.");
        }
    }
}
