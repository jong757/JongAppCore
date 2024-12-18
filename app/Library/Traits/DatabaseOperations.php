<?php

namespace App\Library\Traits;

trait DatabaseOperations
{
	//新增
    public function create($table, $data)
    {
        $fields = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO $table ($fields) VALUES ($placeholders)";
        return $this->executeQuery($sql, $data);
    }
	//读取
    public function read($table, $conditions = [], $columns = "*")
    {
        $where = $this->buildWhereClause($conditions);
        $sql = "SELECT $columns FROM $table $where";
        $stmt = $this->executeQuery($sql, $conditions);

        // 获取所有结果
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
	//修改
    public function update($table, $data, $conditions)
    {
        $setClause = implode(", ", array_map(fn($field) => "$field = :$field", array_keys($data)));
        $whereClause = $this->buildWhereClause($conditions);
        $sql = "UPDATE $table SET $setClause $whereClause";
        return $this->executeQuery($sql, array_merge($data, $conditions));
    }
	//查询
    public function delete($table, $conditions)
    {
        $whereClause = $this->buildWhereClause($conditions);
        $sql = "DELETE FROM $table $whereClause";
        return $this->executeQuery($sql, $conditions);
    }
	//条件句子
    private function buildWhereClause($conditions)
    {
        if (empty($conditions)) return "";
        $where = "WHERE " . implode(" AND ", array_map(fn($field) => "$field = :$field", array_keys($conditions)));
        return $where;
    }
	//错误处理
    private function executeQuery($sql, $params)
    {
        try {
            // 执行查询
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute($params);
            return $stmt;  // 返回 PDOStatement 对象
        } catch (PDOException $e) {
            // 记录详细错误日志（用于调试）
            $errorMessage = "Database query error: " . $e->getMessage();
            $errorDetails = "Error occurred during database query execution.";
            // 错误信息详细记录到日志文件
            error_log($errorMessage . " | " . $errorDetails);

            // 友好的错误消息抛出，不暴露堆栈信息
            throw new Exception("There was an issue with the database query. Please check the database configuration and query.");
        }
    }
}
