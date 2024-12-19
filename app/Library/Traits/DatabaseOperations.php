<?php

namespace App\Library\Traits;

trait DatabaseOperations
{
	/**
	 * 执行自定义 SQL 查询
	 *
	 * @param string $sql 自定义的 SQL 查询语句
	 * @param array $params 查询参数（字段 => 值），默认为空数组
	 * @return mixed 返回查询的结果，通常为 PDOStatement 对象
	 * @throws \Exception 如果查询执行失败，抛出异常
	 */
	public function sqlQuery($sql, $params = [])
	{
		return $this->executeQuery($sql, $params);
	}
		
    /**
     * 新增记录到数据库
     *
     * @param string $table 表名
     * @param array $data 要插入的数据（字段 => 值）
     * @return mixed 执行查询的结果
     */
    public function create($table, $data)
    {
        // 构建字段和占位符部分
        $fields = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO $table ($fields) VALUES ($placeholders)";
        return $this->executeQuery($sql, $data); // 执行查询并返回结果
    }

    /**
     * 从数据库读取记录
     *
     * @param string $table 表名
     * @param array $conditions 查询条件（字段 => 值）
     * @param string $columns 要查询的字段，默认为 "*" 查询所有字段
     * @param int|null $limit 返回的最大记录数，默认为 null（不限制）
     * @return array 查询结果，返回关联数组
     */
    public function read($table, $conditions = [], $columns = "*", $limit = null)
    {
        // 构建 WHERE 子句
        $where = $this->buildWhereClause($conditions);

        // 基础 SQL 查询语句
        $sql = "SELECT $columns FROM $table $where";

        // 如果提供了 limit 参数，追加 LIMIT 子句
        if ($limit !== null) {
            $sql .= " LIMIT " . (int)$limit; // 强制将 LIMIT 参数转换为整数，防止 SQL 注入
        }

        // 执行查询并返回结果
        $stmt = $this->executeQuery($sql, $conditions);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC); // 获取所有匹配的结果
    }

    /**
     * 更新数据库记录
     *
     * @param string $table 表名
     * @param array $data 要更新的数据（字段 => 值）
     * @param array $conditions 更新的条件（字段 => 值）
     * @return mixed 执行查询的结果
     */
    public function update($table, $data, $conditions)
    {
        // 构建 SET 子句
        $setClause = implode(", ", array_map(fn($field) => "$field = :$field", array_keys($data)));

        // 构建 WHERE 子句
        $whereClause = $this->buildWhereClause($conditions);

        // 更新 SQL 查询语句
        $sql = "UPDATE $table SET $setClause $whereClause";

        // 执行更新查询
        return $this->executeQuery($sql, array_merge($data, $conditions));
    }

    /**
     * 删除数据库记录
     *
     * @param string $table 表名
     * @param array $conditions 删除的条件（字段 => 值）
     * @return mixed 执行查询的结果
     */
    public function delete($table, $conditions)
    {
        // 构建 WHERE 子句
        $whereClause = $this->buildWhereClause($conditions);

        // 删除 SQL 查询语句
        $sql = "DELETE FROM $table $whereClause";

        // 执行删除查询
        return $this->executeQuery($sql, $conditions);
    }

    /**
     * 构建 WHERE 条件部分
     *
     * @param array $conditions 查询条件（字段 => 值）
     * @return string 返回构建的 WHERE 子句
     */
    private function buildWhereClause($conditions)
    {
        if (empty($conditions)) return ""; // 如果没有条件，则不添加 WHERE 子句

        // 如果有条件，则将条件字段与占位符组成 "field = :field" 形式
        $where = "WHERE " . implode(" AND ", array_map(fn($field) => "$field = :$field", array_keys($conditions)));
        return $where;
    }

    /**
     * 执行 SQL 查询并处理错误
     *
     * @param string $sql 要执行的 SQL 查询语句
     * @param array $params 查询参数（字段 => 值）
     * @return \PDOStatement 返回 PDOStatement 对象
     * @throws \Exception 如果执行查询时发生错误
     */
    private function executeQuery($sql, $params)
    {
        try {
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute($params);
            return $stmt; 
        } catch (\PDOException $e) {
            $errorMessage = "Database query error: " . $e->getMessage();
            $errorDetails = "Error occurred during database query execution.";
            error_log($errorMessage . " | " . $errorDetails);
            throw new \Exception("There was an issue with the database query. Please check the database configuration and query.");
        }
    }
}
