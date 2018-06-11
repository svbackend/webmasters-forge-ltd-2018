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
    ->register('home_index_controller', \Home\Controller\IndexController::class)
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

return $containerBuilder;