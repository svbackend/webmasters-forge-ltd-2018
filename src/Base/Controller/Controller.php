<?php

declare(strict_types=1);

namespace Base\Controller;

use League\Plates\Engine;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

abstract class Controller
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    protected function forward(string $controller, array $path = [], array $query = []): Response
    {
        /** @var $request Request */
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $path['_controller'] = $controller;
        $subRequest = $request->duplicate($query, null, $path);

        return $this->container->get('app')->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
    }

    /**
     * @return Engine
     */
    protected function getTemplate()
    {
        return $this->container->get('view');
    }
}