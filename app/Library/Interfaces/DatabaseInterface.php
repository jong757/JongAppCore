namespace App\Library\Interfaces;

interface DatabaseInterface
{
    public function connect();
    public function query(string $sql, array $params = []);
    public function insert(string $table, array $data): int;
    public function update(string $table, array $data, array $conditions): int;
    public function delete(string $table, array $conditions): int;
}
