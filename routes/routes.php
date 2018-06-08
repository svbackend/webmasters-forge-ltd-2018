<?php

use Symfony\Component\Routing\Route;

$routes = new \Symfony\Component\Routing\RouteCollection();

$routes->add('home', new Route('/', [
    '_controller' => '\Home\Controller\IndexController::indexAction'
]));

$routes->add('login', new Route('/login', [
    '_controller' => '\User\Controller\LoginController::loginAction'
]));

$routes->add('registration', new Route('/registration', [
    '_controller' => '\User\Controller\RegistrationController::registrationAction'
]));

return $routes;