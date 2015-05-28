<?php
/**
 * Created by PhpStorm.
 * User: dejan
 * Date: 28.5.2015
 * Time: 19:07
 */

namespace CQRSDemo\Domain\Model\Event;


class PostWasCreated
{
    private $postId;

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