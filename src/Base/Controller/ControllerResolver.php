<?php

declare(strict_types=1);

namespace Base\Controller;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolver as SymfonyControllerResolver;

final class ControllerResolver extends SymfonyControllerResolver
{
    private $container;

    public function __construct(ContainerInterface $container, LoggerInterface $logger = null)
    {
        $this->container = $container;

        parent::__construct($logger);
    }

    protected function instantiateController($class)
    {
        if ($this->container->has($class) === true) {
            $controller = $this->container->get($class);
        } else {
            $controller = parent::instantiateController($class);
        }

        if ($controller instanceof Controller) {
            $controller->setContainer($this->container);
        }

        return $controller;
    }
}