<?php
/**
 * Created by PhpStorm.
 * User: dejan
 * Date: 18.5.2015
 * Time: 18:25
 */

namespace CQRSDemo\Domain\Model;

/**
 * Class Post
 * @package CQRSDemo\Domain\Model
 */
class Post
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $title;
    /**
     * @var string
     */
    private $content;

    /**
     * @param int $id
     */
    public function __construct($id = null)
    {
        $this->id = $id;
    }


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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

    /**
     * @param $title
     * @param $content
     */
    public function publish($title, $content)
    {
        $this->title = $title;
        $this->content = $content;
    }
}