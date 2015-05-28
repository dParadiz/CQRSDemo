<?php
/**
 * Created by PhpStorm.
 * User: dejan
 * Date: 28.5.2015
 * Time: 22:29
 */

namespace CQRSDemo\Common;


class CommandBus
{
    /**
     * @var CommandHandlerInterface[]
     */
    private $commandHandlers = [];

    /**
     * @param CommandHandlerInterface $commandHandler
     */
    public function subscribe(CommandHandlerInterface $commandHandler)
    {
        $this->commandHandlers[] = $commandHandler;

    }

    /**
     * @param $command
     */
    public function dispatch($command)
    {
        foreach ($this->commandHandlers as $commandHandler) {
            $commandHandler->handle($command);
        }
    }
}