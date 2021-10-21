<?php

namespace App\Services;

use PDO;

class Database extends PDO
{

    /**
     * @var Database
     */
    private static $instance;

    public function __construct($dsn, $username = null, $password = null, $options = null)
    {
        parent::__construct($dsn, $username, $password, $options);

        self::$instance = $this;
    }

    public static function getInstance(): self
    {
        return self::$instance;
    }

    public function create($tableName, $data): int
    {

        $columns = implode(', ', array_keys($data));
        $values = implode(', ', array_fill(0, count($data), '?'));

        $stmt = $this->prepare(sprintf("INSERT INTO {$tableName} (%s) VALUES (%s)", $columns, $values));

        $stmt->execute(array_values($data));

        return $this->lastInsertId();
    }

    public function update($tableName, $data, array $where = []): int
    {
        $columns = implode(' AND ', array_map(function ($column) {
            return "{$column} = ?";
        }, array_keys($where)));

        $stmt = $this->prepare(sprintf("UPDATE {$tableName} SET %s WHERE %s",
            $this->getColumns($data), $columns));

        $stmt->execute(array_merge([], array_values($data), array_values($where)));

        return $stmt->rowCount();
    }

    public function delete($tableName, array $where = []): int
    {
        $columns = implode(' AND ', array_map(function ($column) {
            return "{$column} = ?";
        }, array_keys($where)));

        $stmt = $this->prepare(sprintf("DELETE FROM {$tableName} WHERE %s", $columns));

        $stmt->execute(array_values($where));

        return $stmt->rowCount();
    }

    public function select($tableName, array $where = []): array
    {

        $columns = implode(' AND ', array_map(function ($column) {
            return "{$column} = ?";
        }, array_keys($where)));

        $stmt = $this->prepare(sprintf("SELECT * FROM {$tableName} WHERE %s", $columns));

        $stmt->execute($where);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectOnce($tableName, array $where = [])
    {
        $columns = implode(' AND ', array_map(function ($column) {
            return "{$column} = ?";
        }, array_keys($where)));

        $stmt = $this->prepare(sprintf("SELECT * FROM {$tableName} WHERE %s", $columns));

        $stmt->execute(array_values($where));

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getColumns($data): string
    {
        return implode(', ', array_map(function ($column) {
            return "{$column} = ?";
        }, array_keys($data)));
    }
}
