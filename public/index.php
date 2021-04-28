<?php

use App\Framework\Container;
use App\Framework\Router;

include __DIR__ . '/../config/bootstrap.php';


Router::add(
    '/',
    function () {
        $controller = Container::getInstance()->get(\App\Controller\HomeController::class);
        echo $controller->index();
    },
    'get'
);

Router::add(
    '/games/user/([0-9]*)',
    function ($var1) {
        $controller = Container::getInstance()->get(\App\Controller\GameController::class);
        echo $controller->index($var1);
    },
    'get'
);

Router::add(
    '/game/add',
    function () {
        $controller = Container::getInstance()->get(\App\Controller\GameController::class);
        echo $controller->createGame((int)$_POST['id']);
    },
    'post'
);

Router::add(
    '/game/select',
    function () {
        $controller = Container::getInstance()->get(\App\Controller\GameController::class);
        echo $controller->selectGame((int)$_GET['id']);
    },
    'get'
);

Router::add(
    '/game/hit',
    function () {
        $controller = Container::getInstance()->get(\App\Controller\GameController::class);
        echo $controller->hit((int)$_POST['id']);
    },
    'post'
);

Router::add(
    '/user/add',
    function () {
        /** @var \App\Domain\Repository\UserRepository $repo */
        $repo = Container::getInstance()->get(\App\Domain\Repository\UserRepository::class);
        /** @var \App\Service\PayloadService $payload */
        $payload = Container::getInstance()->get(\App\Service\PayloadService::class);
        /** @var \App\Framework\View $view */
        $view = Container::getInstance()->get(\App\Framework\View::class);

        $newUserId = $repo->insert(uniqid());
        $payload->pushData('new_user_id', $newUserId);

        echo $view->jsonRender($payload);
    },
    'post'
);


Router::pathNotFound(function (){
    echo 'Page not found';
});

Router::methodNotAllowed(function (){
    echo 'Method not allowed';
});

Router::run();