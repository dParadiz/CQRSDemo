<?php
/**
 * Created by PhpStorm.
 * User: dejan
 * Date: 3.6.2015
 * Time: 19:22
 */
namespace App\Controller;

use CQRSDemo\Common\StorageInterface;
use CQRSDemo\Domain\Model\PostRepository;
use CQRSDemo\Domain\ReadModel\ListedPostRepository;
use CQRSDemo\Domain\ReadModel\PublishedPostProjector;
use CQRSDemo\Domain\ReadModel\PublishedPostRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use CQRSDemo\Domain\Model\Command;


class IndexController
{
    /**
     * @var ContainerInterface
     */
    protected $serviceContainer;

    /**
     * @param ContainerInterface $serviceContainer
     */
    public function __construct(ContainerInterface $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }


    public function browseAction()
    {
        /** @var StorageInterface $storage */
        $storage = $this->serviceContainer->get('data_storage');
        $repository = new ListedPostRepository($storage);
        $listedPosts = $repository->findAll();


        $render = $this->serviceContainer->get('render');
        return new Response($render->render('list.twig', [
            'posts' => $listedPosts
        ]));
    }

    public function readAction(Request $request)
    {
        /** @var StorageInterface $storage */
        $storage = $this->serviceContainer->get('data_storage');

        $repository = new PublishedPostRepository($storage);
        $publishedPost = $repository->find($request->get('id'));

        $render = $this->serviceContainer->get('render');
        return new Response($render->render('read.twig', ['post' => $publishedPost]));
    }

    public function editAction(Request $request)
    {
        /** @var StorageInterface $storage */
        $storage = $this->serviceContainer->get('data_storage');
        $repository = new PublishedPostRepository($storage);
        $post = $repository->find($request->get('id'));

        $render = $this->serviceContainer->get('render');
        return new Response($render->render('edit.twig', ['post' => $post]));
    }

    public function publishAction(Request $request)
    {
        $id = $request->get('id');

        /** @var StorageInterface $storage */
        $storage = $this->serviceContainer->get('data_storage');

        $eventBus = $this->serviceContainer->get('event_bus');
        $eventBus->addEventHandler(new PublishedPostProjector(new PublishedPostRepository($storage)));

        $postRepository = new PostRepository($eventBus);
        $commandBus = $this->serviceContainer->get('command_bus');
        $commandBus->subscribe(new Command\Handler\CreatePostHandler($postRepository));
        $commandBus->subscribe(new Command\Handler\PublishPostHandler($postRepository));

        if (empty($id)) {
            $uiGenerator = $this->serviceContainer->get('guid_generator');
            $id = $uiGenerator->generate();
            $commandBus->dispatch(new Command\CreatePost($id));
        }

        $commandBus->dispatch(new Command\PublishPost($id, $request->get('title'), $request->get('content')));

        return new RedirectResponse('/');
    }

    public function deleteAction(Request $request)
    {
        /** @var StorageInterface $storage */
        $storage = $this->serviceContainer->get('data_storage');

        $eventBus = $this->serviceContainer->get('event_bus');
        $eventBus->addEventHandler(new PublishedPostProjector(new PublishedPostRepository($storage)));

        $commandBus = $this->serviceContainer->get('command_bus');
        $commandBus->subscribe(new Command\Handler\RemovePostHandler(new PostRepository($eventBus)));

        $commandBus->dispatch(new Command\RemovePost($request->get('id')));
        return new RedirectResponse('/');
    }
}