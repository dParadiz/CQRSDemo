<?php
/**
 * Created by PhpStorm.
 * User: dejan
 * Date: 28.5.2015
 * Time: 22:24
 */

namespace CQRSDemo\Domain\Model\Command\Handler;


use CQRSDemo\Common\CommandHandlerInterface;
use CQRSDemo\Domain\Model\Command\RemovePost;
use CQRSDemo\Domain\Model\Post;

class RemovePostHandler extends PostHandler implements CommandHandlerInterface
{
    /**
     * @param RemovePost $command
     */
    public function handleRemovePost(RemovePost $command)
    {
        $post = new Post($command->getPostId());
        $post->remove();
        $this->getPostRepository()->save($post);
    }
}