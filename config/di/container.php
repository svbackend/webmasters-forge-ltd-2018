<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpKernel;
use Symfony\Component\Routing;
use Symfony\Component\EventDispatcher;
use Base\App;

$containerBuilder = new DependencyInjection\ContainerBuilder();
$containerBuilder->register('context', Routing\RequestContext::class);
$containerBuilder->register('matcher', Routing\Matcher\UrlMatcher::class)
    ->setArguments([$routes, new Reference('context')])
;
$containerBuilder->register('request_stack', HttpFoundation\RequestStack::class);
$containerBuilder->register('controller_resolver', \Base\Controller\ControllerResolver::class)
    ->setArguments([
        new Reference('service_container'),
    ]);
$containerBuilder->register('argument_resolver', HttpKernel\Controller\ArgumentResolver::class);

$containerBuilder->register('listener.request', \Base\EventListener\LocaleListener::class)
    ->setArguments([
        new Reference('translation_service'),
        $config['locales'],
        $config['locale']
    ])
;
$containerBuilder->register('listener.router', HttpKernel\EventListener\RouterListener::class)
    ->setArguments([new Reference('matcher'), new Reference('request_stack')])
;
$containerBuilder->register('listener.response', HttpKernel\EventListener\ResponseListener::class)
    ->setArguments(['UTF-8'])
;
$containerBuilder->register('listener.exception', HttpKernel\EventListener\ExceptionListener::class)
    ->setArguments(['Base\Controller\ErrorController::exceptionAction'])
;

$containerBuilder->register('dispatcher', EventDispatcher\EventDispatcher::class)
    ->addMethodCall('addSubscriber', [new Reference('listener.request')])
    ->addMethodCall('addSubscriber', [new Reference('listener.router')])
    ->addMethodCall('addSubscriber', [new Reference('listener.response')])
    ->addMethodCall('addSubscriber', [new Reference('listener.exception')])
;
$containerBuilder->register('app', App::class)
    ->setArguments([
        new Reference('dispatcher'),
        new Reference('controller_resolver'),
        new Reference('request_stack'),
        new Reference('argument_resolver'),
    ])
;
$containerBuilder
    ->register('\Home\Controller\IndexController', \Home\Controller\IndexController::class)
    ->setArguments([
        new Reference('validator_service'),
        new Reference('user_registration_service'),
        new Reference('user_auth_service'),
    ])
    ->addMethodCall('setContainer', [new Reference('service_container')]);
$containerBuilder->register('view', \Base\Service\TemplateService::class)
    ->addMethodCall('setDirectory', [APP_ROOT . '/views'])
    ->addMethodCall('setTranslationService', [new Reference('translation_service')])
    ->addMethodCall('setImageService', [new Reference('thumbnail_service')])
    ->addMethodCall('initCustomTemplateFunctions')
;
$containerBuilder->register('thumbnail_service', \Base\Service\ThumbnailService::class)
    ->setArguments([
        APP_ROOT,
        APP_ROOT . '/public/files/cache',
        '/files/cache',
        null
    ])
;
$containerBuilder->register('translation_service', \Base\Service\TranslationService::class)
    ->setArguments([
        $config['locales'],
        APP_ROOT . '/translations'
    ])
;
$containerBuilder->register('validator_service', \Base\Service\ValidatorService::class);
$containerBuilder->register('user_registration_service', \User\Service\RegistrationService::class)
    ->setArguments([
        new Reference('user_repository'),
    ])
;
$containerBuilder->register('user_auth_service', \User\Service\AuthService::class)
    ->setArguments([
        new Reference('user_repository'),
    ])
;
$containerBuilder->register('user_repository', \User\Repository\UserRepository::class)
    ->setArguments([
        new Reference('db_connection'),
    ])
;
$containerBuilder->register('db_connection', \PDO::class)
    ->setArguments([
        $config['db']['dsn'], $config['db']['username'], $config['db']['password']
    ])
;

return $containerBuilder;