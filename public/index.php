<?php

include '../vendor/autoload.php';

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\EventDispatcher\EventDispatcher;

use Symfony\Component\HttpKernel\EventListener\RouterListener;

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;


$baseDir = dirname(__DIR__);


$routes = new RouteCollection();

$routes->add('browse-posts', new Route('/', [
        '_controller' => 'App\Controller\IndexController::browseAction'
    ]
));

$routes->add('read-posts', new Route('/post/{id}', [
        '_controller' => 'App\Controller\IndexController::readAction'
    ]
));

$routes->add('edit-post', new Route('/edit-post/{id}', [
        '_controller' => 'App\Controller\IndexController::editAction',
        'id' => null,
    ]
));


$routes->add('publish-post', new Route('/publish-post', [
        '_controller' => 'App\Controller\IndexController::publishAction',
    ]
));

$routes->add('remove-post', new Route('/remove-post/{id}', [
        '_controller' => 'App\Controller\IndexController::deleteAction',
    ]
));


$request = Request::createFromGlobals();

$matcher = new UrlMatcher($routes, new RequestContext());

$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber(new RouterListener($matcher));

/** @var ContainerBuilder $serviceContainer */
$serviceContainer = include $baseDir . '/app/services.php';

$resolver = new App\ControllerResolver();
$resolver->setServiceContainer($serviceContainer);

$kernel = new HttpKernel($dispatcher, $resolver);
$response = $kernel->handle($request);
$response->send();

$kernel->terminate($request, $response);