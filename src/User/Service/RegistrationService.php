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
    
    public function register(string $login, string $email, string $password, ?string $about = '', ?int $gender = 0): void
    {
        $user = compact($login, $email, $password, $about, $gender);
        $this->registeredUser = $this->repository->save($user);
    }

    public function getRegisteredUser(): ?array
    {
        return $this->registeredUser;
    }
}