<?php
/**
 * Created by PhpStorm.
 * User: dejan
 * Date: 19.5.2015
 * Time: 21:19
 */

namespace CQRSDemo\Common;


class ConventionBasedProjector implements Projector
{

    /**
     * @param $event
     */
    public function handle($event)
    {
        $method = $this->getHandleMethod($event);

        if (!method_exists($this, $method)) {
            return;
        }

        $this->$method($event);
        // TODO: Implement handle() method.
    }

    private function getHandleMethod($event)
    {
        $classPaths = explode('\\', get_class($event));

        return 'apply' . end($classPaths);
    }
}