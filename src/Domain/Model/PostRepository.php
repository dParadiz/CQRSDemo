<?php

namespace CQRSDemo\Domain\Model;

use CQRSDemo\Common\StorageInterface;

/**
 * Class PostRepository
 * @package CQRSDemo\Domain\Model
 */
class PostRepository
{
    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * @param StorageInterface $storage
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param int $id
     * @return Post
     */
    public function find($id)
    {
        $postData = $this->storage->getData($id);

        $post = new Post($id);

        if (null !== $postData) {
            $post->publish($postData['title'], $postData['content']);
        }

        return $post;
    }

    /**
     * @return Post[]
     */
    public function findAll()
    {
        $content = $this->storage->getStoredContent();
        $postList = [];
        foreach ($content as $postId => $postData) {
            $post = new Post($postId);
            $post->publish($postData['title'], $postData['content']);
            $postList[] = $post;
        }
        return $postList;
    }

    /**
     * @param int $id
     */
    public function removePost($id)
    {
        $this->storage->removeData($id);
    }

    /**
     * @param Post $post
     */
    public function save($post)
    {
        $this->storage->addData([
            'title' => $post->getTitle(),
            'content' => $post->getContent()
        ]);
    }
}