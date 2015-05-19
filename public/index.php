<?php

include '../vendor/autoload.php';

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
use CQRSDemo\Domain\Model\Post;

$baseDir = dirname(__DIR__);

$templateLoader = new Twig_Loader_Filesystem($baseDir . '/app/templates');
$twig = new Twig_Environment($templateLoader);

$routes = new RouteCollection();
$routes->add('browse-posts', new Route('/', [
        '_controller' => function () use ($twig) {

            $postRepository = new PostRepository(new \Data\Storage());
            $postList = $postRepository->findAll();

            return new Response($twig->render('list.twig', ['posts' => $postList]));
        }
    ]
));

$routes->add('read-posts', new Route('/post/{id}', [
        '_controller' => function (Request $request) use ($twig) {

            $postRepository = new PostRepository(new \Data\Storage());
            $post = $postRepository->find((int)$request->get('id'));

            return new Response($twig->render('read.twig', ['post' => $post]));
        }
    ]
));

$routes->add('edit-posts', new Route('/publish-post', [
        '_controller' => function (Request $request) {

            $post = new Post();
            $post->publish($request->get('title'), $request->get('content'));

            $postRepository = new PostRepository(new \Data\Storage());
            $postRepository->save($post);

            return new RedirectResponse('/');
        }
    ]
));

$routes->add('remove-post', new Route('/remove-post/{id}', [
        '_controller' => function (Request $request) {

            $postRepository = new PostRepository(new \Data\Storage());
            $postRepository->removePost($request->get('id'));

            return new RedirectResponse('/');
        }
    ]
));

$routes->add('edit-post', new Route('/edit-post/{id}', [
        '_controller' => function (Request $request) use ($twig) {
            $post = new Post();
            if (null !== $request->get('id')) {
                $postRepository = new PostRepository(new \Data\Storage());
                $post = $postRepository->find($request->get('id'));
            }
            return new Response($twig->render('edit.twig', ['post' => $post]));
        },
        'id' => null,
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