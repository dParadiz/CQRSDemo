<?php
/**
 * Created by PhpStorm.
 * User: dejan
 * Date: 18.5.2015
 * Time: 18:25
 */

namespace CQRSDemo\Domain\Model;

use CQRSDemo\Common\RecordsEvents;
use CQRSDemo\Domain\Model\Event;


/**
 * Class Post
 * @package CQRSDemo\Domain\Model
 */
class Post implements RecordsEvents
{
    /**
     * @var array
     */
    private $recordedEvents = [];
    /**
     * @var int
     */
    private $id;

    /**
     * @param int $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @param $id
     * @return static
     */
    public static function create($id)
    {
        $instance = new static($id);
        $instance->recordEvent(new Event\PostWasCreated($id));
        return $instance;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @param $title
     * @param $content
     */
    public function publish($title, $content)
    {
        $this->recordEvent(new Event\PostWasPublished($this->id, $title, $content));
    }

    /**
     * @return mixed
     */
    public function getRecordedEvents()
    {
        return $this->recordedEvents;
    }


    public function remove()
    {
        $this->recordEvent(new Event\PostWasRemoved($this->id));
    }

    /**
     * @param $event
     */
    public function recordEvent($event)
    {
        $this->recordedEvents[] = $event;
    }
}