<?php
/**
 * Created by PhpStorm.
 * User: dejan
 * Date: 28.5.2015
 * Time: 20:01
 */

namespace CQRSDemo\Domain\ReadModel;


use CQRSDemo\Common\StorageInterface;

class PublishedPostRepository
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
     * @return PublishedPost
     */
    public function find($id)
    {
        $postData = $this->storage->getData($id);

        $publishedPost = new PublishedPost($id);

        if (null !== $postData) {
            $publishedPost->title = $postData['title'];
            $publishedPost->content = $postData['content'];
        }

        return $publishedPost;
    }

    /**
     * @param PublishedPost $publishedPost
     */
    public function save(PublishedPost $publishedPost)
    {
        $this->storage->addData(
            $publishedPost->id,
            [
                'id' => $publishedPost->id,
                'title' => $publishedPost->title,
                'content' => $publishedPost->content
            ]
        );
    }

    /**
     * @param PublishedPost $publishedPost
     */
    public function remove(PublishedPost $publishedPost)
    {
        $this->storage->removeData($publishedPost->id);
    }
}