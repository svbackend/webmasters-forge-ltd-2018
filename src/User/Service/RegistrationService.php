<?php

declare(strict_types=1);

namespace User\Service;

use User\Repository\UserRepository;

class RegistrationService
{
    private $repository;
    private $registeredUser;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }
    
    public function register(string $first_name, string $last_name, string $login, string $email, string $password, ?string $information = '', ?int $gender = 0): void
    {
        $user = compact('first_name', 'last_name', 'login', 'email', 'password', 'information', 'gender');
        $user['password'] = password_hash($user['password'], PASSWORD_ARGON2I);
        $user['created_at'] = time();
        $user['updated_at'] = time();
        $this->registeredUser = $this->repository->save($user);
    }

    public function getRegisteredUser(): ?array
    {
        return $this->registeredUser;
    }
}