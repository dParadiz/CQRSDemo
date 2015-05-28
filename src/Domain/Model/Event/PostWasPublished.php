<?php
/**
 * Created by PhpStorm.
 * User: dejan
 * Date: 28.5.2015
 * Time: 19:07
 */

namespace CQRSDemo\Domain\Model\Event;


class PostWasPublished
{
    private $title;
    private $content;
    private $postId;

    public function __construct($postId, $title, $content)
    {
        $this->title = $title;
        $this->content = $content;
        $this->postId = $postId;
    }

    /**
     * @return mixed
     */
    public function getPostId()
    {
        return $this->postId;
    }


    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }


}