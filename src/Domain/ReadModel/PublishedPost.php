<?php
/**
 * Created by PhpStorm.
 * User: dejan
 * Date: 28.5.2015
 * Time: 19:53
 */

namespace CQRSDemo\Domain\ReadModel;


class PublishedPost
{
    public $id;
    public $content;
    public $title;

    /**
     * @param $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

}