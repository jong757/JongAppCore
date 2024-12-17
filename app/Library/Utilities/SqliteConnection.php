namespace App\Library\Utilities;

use App\Library\Interfaces\DatabaseInterface;
use PDO;
use Exception;

class SqliteConnection implements DatabaseInterface
{
    private $connection;
    private $config;

    /**
     * 构造函数，接受数据库配置
     *
     * @param array $config 数据库配置
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * 获取数据库连接
     *
     * @return PDO
     * @throws Exception
     */
    public function connect(): PDO
    {
        if (!$this->connection) {
            try {
                $dsn = "sqlite:{$this->config['path']}";
                $this->connection = new PDO($dsn);
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $this->connection;
            } catch (Exception $e) {
                throw new Exception("Failed to connect to SQLite: " . $e->getMessage());
            }
        }

        return $this->connection;
    }

    /**
     * 执行 SQL 查询
     *
     * @param string $sql SQL 查询语句
     * @param array $params 查询参数
     * @return mixed
     * @throws Exception
     */
    public function query(string $sql, array $params = [])
    {
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
