<?php
/**
 * Created by PhpStorm.
 * User: dejan
 * Date: 28.5.2015
 * Time: 21:34
 */

namespace CQRSDemo\Domain\Model\Event;


class PostWasRemoved
{
    private $postId;

    /**
     * @param $postId
     */
    public function __construct($postId)
    {
        $this->postId = $postId;
    }

    /**
     * @return mixed
     */
    public function getPostId()
    {
        return $this->postId;
    }
}