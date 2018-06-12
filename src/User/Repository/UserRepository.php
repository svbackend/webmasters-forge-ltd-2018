<?php

declare(strict_types=1);

namespace User\Repository;

class UserRepository extends DbRepository
{
    public function getTable()
    {
        return 'users';
    }
    
    public function save(array $user): array
    {
        $sql = "INSERT INTO {$this->getTable()} ({$this->getFieldsList()}) VALUES ({$this->getFieldsList(':')})";
        $query = $this->pdo->prepare("INSERT INTO {$this->table} ({$this->getFieldsList()}) VALUES ({$this->getFieldsList(':')})");
        $this->beforeSave();
        if ($query && $query->execute($this->getValues())) {
            $this->id = $this->db->lastInsertId();
            $this->afterSave();
            return $this;
        } else {
            throw new \PDOException('Something goes wrong..');
        }
    }
}