<?php

namespace App\Library\Traits;

trait DatabaseOperations
{
    public function create($table, $data)
    {
        $fields = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO $table ($fields) VALUES ($placeholders)";
        return $this->executeQuery($sql, $data);
    }

    public function read($table, $conditions = [], $columns = "*")
    {
        $where = $this->buildWhereClause($conditions);
        $sql = "SELECT $columns FROM $table $where";
        $stmt = $this->executeQuery($sql, $conditions);

        // 获取所有结果
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function update($table, $data, $conditions)
    {
        $setClause = implode(", ", array_map(fn($field) => "$field = :$field", array_keys($data)));
        $whereClause = $this->buildWhereClause($conditions);
        $sql = "UPDATE $table SET $setClause $whereClause";
        return $this->executeQuery($sql, array_merge($data, $conditions));
    }

    public function delete($table, $conditions)
    {
        $whereClause = $this->buildWhereClause($conditions);
        $sql = "DELETE FROM $table $whereClause";
        return $this->executeQuery($sql, $conditions);
    }

    private function buildWhereClause($conditions)
    {
        if (empty($conditions)) return "";
        $where = "WHERE " . implode(" AND ", array_map(fn($field) => "$field = :$field", array_keys($conditions)));
        return $where;
    }

    private function executeQuery($sql, $params)
    {
        try {
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute($params);
            return $stmt;  // 返回 PDOStatement 对象
        } catch (\PDOException $e) {
            throw new \Exception("Database query error: " . $e->getMessage());
        }
    }
}
