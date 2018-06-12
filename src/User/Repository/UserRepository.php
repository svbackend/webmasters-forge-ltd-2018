<?php

declare(strict_types=1);

namespace User\Repository;

use Base\Repository\DbRepository;

class UserRepository extends DbRepository
{
    public function getTable(): string
    {
        return 'users';
    }

    public function getIdColumn(): string
    {
        return 'id';
    }

    public function find(int $id): ?array
    {
        $query = $this->pdo->prepare("SELECT * FROM {$this->getTable()} WHERE `{$this->getIdColumn()}` = :id LIMIT 1");
        $query->execute([
            'id' => $id
        ]);
        $result = $query->fetchAll();
        $user = reset($result);
        return $user ?? null;
    }

    public function findOneByLoginOrEmail(string $login): ?array
    {
        $query = $this->pdo->prepare("SELECT * FROM {$this->getTable()} WHERE `login` = :login OR `email` = :login LIMIT 1");
        $query->execute([
            'login' => $login
        ]);
        $result = $query->fetchAll();
        $user = reset($result);
        return $user ?? null;
    }
}