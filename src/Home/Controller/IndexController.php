<?php

declare(strict_types=1);

namespace Home\Controller;

use Base\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends Controller
{
    public function indexAction(Request $request): Response
    {
        $response = $this->getTemplate()->make('home/index');
        $response->data([
            'thumb' => $this->container->get('thumbnail_service')
        ]);
        return new Response($response);
    }
}