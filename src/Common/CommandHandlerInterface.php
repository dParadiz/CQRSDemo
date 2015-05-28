<?php
/**
 * Created by PhpStorm.
 * User: dejan
 * Date: 28.5.2015
 * Time: 22:35
 */

namespace CQRSDemo\Common;


interface CommandHandlerInterface
{
    public function handle($command);
}