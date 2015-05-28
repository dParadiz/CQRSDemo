<?php
/**
 * Created by PhpStorm.
 * User: dejan
 * Date: 28.5.2015
 * Time: 21:07
 */

namespace CQRSDemo\Common;

class EventBus
{
    /**
     * @var Projector[]
     */
    protected $handlers = [];

    public function addEventHandler(Projector $eventHandler)
    {
        $this->handlers[] = $eventHandler;
    }

    public function dispatch($event)
    {
        foreach ($this->handlers as $eventHandler) {
            $eventHandler->handle($event);
        }
    }
}