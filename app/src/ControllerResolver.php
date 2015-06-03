<?php


namespace App;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolver as SymfonyControllerResolver;

class ControllerResolver extends SymfonyControllerResolver
{
    /**
     * @var ContainerInterface
     */
    private $serviceContainer;

    /**
     * @param ContainerInterface $container
     */
    public function setServiceContainer(ContainerInterface $container)
    {
        $this->serviceContainer = $container;
    }


    protected function instantiateController($controller)
    {
        return $this->serviceContainer->get($controller);
    }
}