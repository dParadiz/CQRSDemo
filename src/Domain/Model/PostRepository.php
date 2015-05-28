<?php

namespace CQRSDemo\Domain\Model;

use CQRSDemo\Common\EventBus;

/**
 * Class PostRepository
 * @package CQRSDemo\Domain\Model
 */
class PostRepository
{

    /**
     * @var EventBus
     */
    private $eventBus;

    /**
     * @param EventBus $eventBus
     */
    public function __construct(EventBus $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    /**
     * @param Post $post
     */
    public function save($post)
    {
        foreach ($post->getRecordedEvents() as $event) {
            $this->eventBus->dispatch($event);
        }
    }

}