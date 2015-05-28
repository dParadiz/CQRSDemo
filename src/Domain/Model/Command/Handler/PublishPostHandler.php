<?php
/**
 * Created by PhpStorm.
 * User: dejan
 * Date: 28.5.2015
 * Time: 22:25
 */

namespace CQRSDemo\Domain\Model\Command\Handler;

use CQRSDemo\Domain\Model\Command\PublishPost;
use CQRSDemo\Domain\Model\Post;

class PublishPostHandler extends PostHandler
{
    /**
     * @param PublishPost $command
     */
    public function handlePublishPost(PublishPost $command)
    {
        $post = new Post($command->getPostId());
        $post->publish($command->getTitle(), $command->getContent());

        $this->getPostRepository()->save($post);
    }
}