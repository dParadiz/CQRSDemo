<?php
/**
 * Created by PhpStorm.
 * User: dejan
 * Date: 19.5.2015
 * Time: 21:18
 */

namespace CQRSDemo\Common;


interface Projector
{
    /**
     * @param $event
     */
    public function handle($event);
}