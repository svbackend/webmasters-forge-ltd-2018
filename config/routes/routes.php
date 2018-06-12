<?php

use Symfony\Component\Routing\Route;

$routes = new \Symfony\Component\Routing\RouteCollection();

$routes->add('home', new Route('/', [
    '_controller' => '\Home\Controller\IndexController::indexAction'
]));

$routes->add('login', new Route('/login', [
    '_controller' => '\Home\Controller\IndexController::loginAction'
]));

$routes->add('registration', new Route('/registration', [
    '_controller' => '\Home\Controller\IndexController::registrationAction'
]));

$routes->add('user_view', new Route('/user/{id}', [
    '_controller' => '\Home\Controller\IndexController::userAction'
]));

$routes->add('user_logout', new Route('/logout', [
    '_controller' => '\Home\Controller\IndexController::logoutAction'
]));

$routes->add('switch_locale', new Route('/lang/{locale}', [
    '_controller' => '\Home\Controller\IndexController::langAction'
]));

return $routes;