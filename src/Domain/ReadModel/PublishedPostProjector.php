<?php
/**
 * Created by PhpStorm.
 * User: dejan
 * Date: 28.5.2015
 * Time: 20:47
 */

namespace CQRSDemo\Domain\ReadModel;


use CQRSDemo\Common\ConventionBasedProjector;
use CQRSDemo\Domain\Model\Event;


class PublishedPostProjector extends ConventionBasedProjector
{

    private $repository;

    public function __construct(PublishedPostRepository $repository)
    {
        $this->repository = $repository;
    }


    public function applyPostWasCreated(Event\PostWasCreated $event)
    {
        $publishedPost = new PublishedPost($event->getPostId());
        $this->repository->save($publishedPost);

    }

    public function applyPostWasPublished(Event\PostWasPublished $event)
    {
        $publishedPost = new PublishedPost($event->getPostId());
        $publishedPost->title = $event->getTitle();
        $publishedPost->content = $event->getContent();

        $this->repository->save($publishedPost);
    }

    public function applyPostWasRemoved(Event\PostWasRemoved $event)
    {
        $publishedPost = new PublishedPost($event->getPostId());
        $this->repository->remove($publishedPost);

    }
}