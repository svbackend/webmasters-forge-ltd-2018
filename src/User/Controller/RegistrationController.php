<?php

declare(strict_types=1);

namespace User\Controller;

use Symfony\Component\HttpFoundation\Response;

class RegistrationController
{
    public function loginAction()
    {
        return new Response('Hello, World! (registration controller)');
    }
}