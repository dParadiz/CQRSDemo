<?php
/**
 * Created by PhpStorm.
 * User: dejan
 * Date: 28.5.2015
 * Time: 19:54
 */

namespace CQRSDemo\Domain\ReadModel;


use CQRSDemo\Common\StorageInterface;

class ListedPostRepository
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
     * @return ListedPost[]
     */
    public function findAll()
    {
        $content = $this->storage->getStoredContent();
        $postList = [];
        foreach ($content as $postId => $postData) {
            $listedPost = new ListedPost();
            $listedPost->id = $postId;
            $listedPost->title = $postData['title'];
            $postList[] = $listedPost;
        }
        return $postList;
    }
}