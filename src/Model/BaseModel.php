<?php

namespace App\Model;

use App\Services\Database;

abstract class BaseModel
{

    /**
     * @var Database
     */
    private $database;

    protected $tableName;

    public function __construct()
    {
        $this->database = Database::getInstance();
    }

    public function find($id): array
    {
        return $this->database->selectOnce($this->tableName, ['id' => $id]);
    }

    public function selectOnce($where)
    {
        return $this->database->selectOnce($this->tableName, $where);
    }

    public function create($data): int
    {
        return $this->database->create($this->tableName, $data);
    }

    public function update($data, $where): int
    {
        return $this->database->update($this->tableName, $data, $where);
    }

    public function delete($where): int
    {
        return $this->database->delete($this->tableName, $where);
    }
}
