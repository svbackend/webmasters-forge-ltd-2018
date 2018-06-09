<?php

declare(strict_types=1);

use Symfony\Component\HttpFoundation\Request;

require __DIR__.'/../vendor/autoload.php';

$request = Request::createFromGlobals();
$routes = require __DIR__ . '/../config/routes/routes.php';
$container = require __DIR__.'/../config/di/container.php';

/** @var $application \Base\App */
$application = $container->get('app');
$application->handle($request)->send();