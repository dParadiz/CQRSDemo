<?php
/**
 * Created by PhpStorm.
 * User: dejan
 * Date: 28.5.2015
 * Time: 22:25
 */

namespace CQRSDemo\Domain\Model\Command\Handler;

use CQRSDemo\Common\CommandHandlerInterface;
use CQRSDemo\Domain\Model\Command\CreatePost;
use CQRSDemo\Domain\Model\Post;

class CreatePostHandler extends PostHandler implements CommandHandlerInterface
{
    /**
     * @param CreatePost $command
     */
    public function handleCreatePost(CreatePost $command)
    {
        $post = Post::create($command->getPostId());
        $this->getPostRepository()->save($post);
    }
}