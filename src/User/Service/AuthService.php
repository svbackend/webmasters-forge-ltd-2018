<?php

declare(strict_types=1);

namespace User\Service;

use User\Repository\UserRepository;

class AuthService
{
    private $repository;
    private $authenticatedUser;
    private $errors = [];

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string $login can be username or email
     * @param string $password plain password
     */
    public function login(string $login, string $password): void
    {
        if (null === $user = $this->repository->findOneByLoginOrEmail($login)) {
            $this->errors['login'] = 'User not exists';
            return;
        }

        if (false === password_verify($password, $user['password'])) {
            $this->errors['password'] = 'Invalid password';
            return;
        }

        $_SESSION['user_id'] = $user['id'];
        $this->authenticatedUser = $user;
    }

    public function logout()
    {
        unset($_SESSION['user_id']);
    }

    public function isAuthenticated()
    {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getAuthenticatedUser(): ?array
    {
        return $this->authenticatedUser;
    }
}