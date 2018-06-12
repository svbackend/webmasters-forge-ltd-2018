<?php

declare(strict_types=1);

namespace Base\Repository;

abstract class DbRepository
{
    /**
     * @var \PDO
     */
    protected $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    abstract public function getIdColumn(): string;
    abstract public function getTable(): string;

    public function save(array $entity)
    {
        $idKey = $this->getIdColumn();

        if (isset($entity[$idKey]) && !empty($entity[$idKey])) {
            return $this->updateEntity($entity);
        }

        return $this->createEntity($entity);
    }

    private function updateEntity(array $entity): array
    {
        // todo
    }

    private function createEntity(array $entity): array
    {
        $fields = $this->getFieldsList(array_keys($entity));
        $valuesPlaceholders = $this->getFieldsList($entity, ':');
        $query = $this->pdo->prepare("INSERT INTO {$this->getTable()} ({$fields}) VALUES ({$valuesPlaceholders})");

        if ($query && $query->execute($entity)) {
            $entity[$this->getIdColumn()] = $this->pdo->lastInsertId();
            return $entity;
        } else {
            throw new \PDOException('Something goes wrong..');
        }
    }


    private function getFieldsList(array $fields, $prefix = null): string
    {
        $list = '';
        foreach ($fields as $field) {
            if ($prefix === null) {
                $list .= "`{$field}`";
            } else {
                $list .= $prefix . $field;
            }
            $list .= ',';
        }
        return substr($list, 0, -1);
    }
}