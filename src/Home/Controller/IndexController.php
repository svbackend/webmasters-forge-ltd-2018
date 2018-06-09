<?php

declare(strict_types=1);

namespace Home\Controller;

use Base\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends Controller
{
    public function indexAction(Request $request)
    {
        return new Response('Hello, World! (Here would be template with login and registration forms)');
    }
}