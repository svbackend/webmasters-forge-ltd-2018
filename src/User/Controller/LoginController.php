<?php

declare(strict_types=1);

namespace User\Controller;

use Base\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    public function loginAction()
    {
        return new Response('Hello, World! (login controller)');
    }
}