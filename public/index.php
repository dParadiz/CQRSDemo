<?php

include '../vendor/autoload.php';

function getGUID()
{
    if (function_exists('com_create_guid')) {
        return com_create_guid();
    } else {
        mt_srand((double)microtime() * 10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = substr($charid, 0, 8) . $hyphen
            . substr($charid, 8, 4) . $hyphen
            . substr($charid, 12, 4) . $hyphen
            . substr($charid, 16, 4) . $hyphen
            . substr($charid, 20, 12);

        return $uuid;
    }
}

use CQRSDemo\Domain\ReadModel\ListedPostRepository;
use CQRSDemo\Domain\ReadModel\PublishedPostProjector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

use CQRSDemo\Domain\Model\PostRepository;
use CQRSDemo\Domain\ReadModel\PublishedPostRepository;
use CQRSDemo\Domain\Model\Command;

$baseDir = dirname(__DIR__);

$templateLoader = new Twig_Loader_Filesystem($baseDir . '/app/templates');
$twig = new Twig_Environment($templateLoader);

$routes = new RouteCollection();

$eventBus = new \CQRSDemo\Common\EventBus();
$commandBus = new \CQRSDemo\Common\CommandBus();

$routes->add('browse-posts', new Route('/', [
        '_controller' => function () use ($twig) {

            $repository = new ListedPostRepository(new \Data\Storage());
            $postList = $repository->findAll();

            return new Response($twig->render('list.twig', ['posts' => $postList]));
        }
    ]
));

$routes->add('read-posts', new Route('/post/{id}', [
        '_controller' => function (Request $request) use ($twig) {

            $repository = new PublishedPostRepository(new \Data\Storage());
            $publishedPost = $repository->find($request->get('id'));

            return new Response($twig->render('read.twig', ['post' => $publishedPost]));
        }
    ]
));

$routes->add('edit-post', new Route('/edit-post/{id}', [
        '_controller' => function (Request $request) use ($twig) {

            $repository = new PublishedPostRepository(new \Data\Storage());
            $post = $repository->find($request->get('id'));

            return new Response($twig->render('edit.twig', ['post' => $post]));
        },
        'id' => null,
    ]
));

//command section
$routes->add('publish-post', new Route('/publish-post', [
        '_controller' => function (Request $request) use ($eventBus, $commandBus) {

            $id = $request->get('id');

            $eventBus->addEventHandler(new PublishedPostProjector(new PublishedPostRepository(new \Data\Storage())));

            $postRepository = new PostRepository($eventBus);

            $commandBus->subscribe(new Command\Handler\CreatePostHandler($postRepository));
            $commandBus->subscribe(new Command\Handler\PublishPostHandler($postRepository));

            if (empty($id)) {
                $id = getGUID();
                $commandBus->dispatch(new Command\CreatePost($id));
            }

            $commandBus->dispatch(new Command\PublishPost($id, $request->get('title'), $request->get('content')));

            return new RedirectResponse('/');
        }
    ]
));

$routes->add('remove-post', new Route('/remove-post/{id}', [
        '_controller' => function (Request $request) use ($eventBus, $commandBus) {

            $eventBus->addEventHandler(new PublishedPostProjector(new PublishedPostRepository(new \Data\Storage())));

            $commandBus->subscribe(new Command\Handler\RemovePostHandler(new PostRepository($eventBus)));

            $commandBus->dispatch(new Command\RemovePost($request->get('id')));
            return new RedirectResponse('/');
        }
    ]
));


$request = Request::createFromGlobals();

$matcher = new UrlMatcher($routes, new RequestContext());

$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber(new RouterListener($matcher));

$resolver = new ControllerResolver();
$kernel = new HttpKernel($dispatcher, $resolver);

$response = $kernel->handle($request);
$response->send();

$kernel->terminate($request, $response);