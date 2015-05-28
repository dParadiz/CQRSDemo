<?php
/**
 * Created by PhpStorm.
 * User: dejan
 * Date: 28.5.2015
 * Time: 22:25
 */

namespace CQRSDemo\Domain\Model\Command;


class PublishPost
{
    private $postId;
    private $title;
    private $content;

    /**
     * @param $postId
     * @param $title
     * @param $content
     */
    public function __construct($postId, $title, $content)
    {
        $this->postId = $postId;
        $this->title = $title;
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getPostId()
    {
        return $this->postId;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }


}