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
}