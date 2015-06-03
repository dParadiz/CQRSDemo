<?php

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

$baseDir = dirname(__DIR__);

$serviceContainer = new ContainerBuilder();

$serviceContainer->register('render', 'Twig_Environment')
    ->addArgument(new Twig_Loader_Filesystem($baseDir . '/app/templates'));
$serviceContainer->register('command_bus', new \CQRSDemo\Common\CommandBus());
$serviceContainer->register('event_bus', new \CQRSDemo\Common\EventBus());
$serviceContainer->register('data_storage', 'App\Data\Storage')->addArgument($baseDir .'/data');
$serviceContainer->register('App\Controller\IndexController', 'App\Controller\IndexController')
    ->addArgument(new Reference('service_container'));

$serviceContainer->register('guid_generator', new Broadway\UuidGenerator\Rfc4122\Version4Generator());


return $serviceContainer;