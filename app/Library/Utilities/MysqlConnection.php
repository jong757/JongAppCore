namespace App\Library\Utilities;

use App\Library\Interfaces\DatabaseInterface;
use PDO;
use Exception;

class MysqlConnection implements DatabaseInterface
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
                $dsn = "mysql:host={$this->config['host']};dbname={$this->config['dbname']}";
                $this->connection = new PDO($dsn, $this->config['username'], $this->config['password']);
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $this->connection;
            } catch (Exception $e) {
                throw new Exception("Failed to connect to MySQL: " . $e->getMessage());
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

    /**
     * 执行 SQL 插入操作
     *
     * @param string $table 表名
     * @param array $data 插入数据
     * @return int 插入操作影响的行数
     */
    public function insert(string $table, array $data): int
    {
        $columns = implode(", ", array_keys($data));
        $values = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$values})";

        $stmt = $this->connect()->prepare($sql);
        foreach ($data as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }
        $stmt->execute();

        return $stmt->rowCount();
    }
}
