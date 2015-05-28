<?php
/**
 * Created by PhpStorm.
 * User: dejan
 * Date: 28.5.2015
 * Time: 22:26
 */

namespace CQRSDemo\Domain\Model\Command\Handler;


use CQRSDemo\Common\CommandHandlerInterface;
use CQRSDemo\Domain\Model\PostRepository;

abstract class PostHandler implements CommandHandlerInterface
{
    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * @param PostRepository $postRepository
     */
    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * @return PostRepository
     */
    public function getPostRepository()
    {
        return $this->postRepository;
    }

    public function handle($command)
    {
        $method = $this->getHandleMethod($command);
        if (!method_exists($this, $method)) {
            return;
        }
        $this->$method($command);
    }

    private function getHandleMethod($command)
    {
        $classParts = explode('\\', get_class($command));
        return 'handle' . end($classParts);
    }


}