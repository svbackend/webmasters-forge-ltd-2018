<?php

declare(strict_types=1);

namespace Home\Controller;

use Symfony\Component\HttpFoundation\Response;

class IndexController
{
    public function indexAction()
    {
        return new Response('Hello, World! (Here would be template with login and registration forms)');
    }
}