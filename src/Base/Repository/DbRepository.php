<?php

declare(strict_types=1);

namespace Base\Repository;

use Base\Exception\UniqueConstraint;

abstract class DbRepository
{
    /**
     * @var \PDO
     */
    protected $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
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

    /**
     * @param array $entity
     * @return array
     * @throws \Exception
     */
    private function createEntity(array $entity): array
    {
        $entityFields = array_keys($entity);
        $fields = $this->getFieldsList($entityFields);
        $valuesPlaceholders = $this->getFieldsList($entityFields, ':');

        $query = $this->pdo->prepare("INSERT INTO {$this->getTable()} ({$fields}) VALUES ({$valuesPlaceholders})");

        try {
            $query->execute($entity);
        } catch (\PDOException $pdoException) {
            $pdoCode = $pdoException->errorInfo[1];
            if ($pdoCode === 1062) {
                throw new UniqueConstraint($pdoException->errorInfo[2]);
            }

            throw $pdoException;
        }
        // id setted here
        $entity[$this->getIdColumn()] = $this->pdo->lastInsertId();
        return $entity;
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