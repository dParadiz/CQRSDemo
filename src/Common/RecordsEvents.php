<?php
/**
 * Created by PhpStorm.
 * User: dejan
 * Date: 19.5.2015
 * Time: 20:42
 */

namespace CQRSDemo\Common;


interface RecordsEvents {

    /**
     * @return mixed
     */
    public function getRecordedEvents();

}