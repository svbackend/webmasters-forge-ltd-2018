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
        $response = $this->getTemplate()->make('home/index');
        return new Response($response);
    }
}