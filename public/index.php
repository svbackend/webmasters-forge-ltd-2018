<?php

declare(strict_types=1);

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;

require __DIR__.'/../vendor/autoload.php';

$request = Request::createFromGlobals();
$routes = include __DIR__.'/../routes/routes.php';

$context = new Routing\RequestContext();
$matcher = new Routing\Matcher\UrlMatcher($routes, $context);

$controllerResolver = new ControllerResolver();
$argumentResolver = new ArgumentResolver();

$framework = new \Base\App($matcher, $controllerResolver, $argumentResolver);
$response = $framework->handle($request);

$response->send();