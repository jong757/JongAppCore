namespace App\Library\Traits;

trait DatabaseTraits
{
    /**
     * 插入数据
     *
     * @param string $table 表名
     * @param array $data 插入的数据
     * @return int
     */
    public function insert(string $table, array $data): int
    {
        return $this->getConnection()->insert($table, $data);
    }

    /**
     * 更新数据
     *
     * @param string $table 表名
     * @param array $data 更新的数据
     * @param array $conditions 更新条件
     * @return int
     */
    public function update(string $table, array $data, array $conditions): int
    {
        $set = implode(" = ?, ", array_keys($data)) . " = ?";
        $where = implode(" AND ", array_map(fn($k) => "{$k} = ?", array_keys($conditions)));
        $sql = "UPDATE {$table} SET {$set} WHERE {$where}";

        $params = array_merge(array_values($data), array_values($conditions));
        return $this->getConnection()->execute($sql, $params);
    }

    /**
     * 删除数据
     *
     * @param string $table 表名
     * @param array $conditions 删除条件
     * @return int
     */
    public function delete(string $table, array $conditions): int
    {
        $where = implode(" AND ", array_map(fn($k) => "{$k} = ?", array_keys($conditions)));
        $sql = "DELETE FROM {$table} WHERE {$where}";

        return $this->getConnection()->execute($sql, array_values($conditions));
    }
}
