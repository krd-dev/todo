<?php

use Delight\Auth\Auth;
use DI\ContainerBuilder;
use League\Plates\Engine;
use Aura\SqlQuery\QueryFactory;

// Предопределяю аргументы (php-di)
$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions([
    Engine::class => function () {
        return new Engine('app/views');
    },
    QueryFactory::class => function () {
        return new QueryFactory('mysql');
    },
    PDO::class => function () {
        return new PDO("mysql:dbname=test;host=localhost", 'root', '');
    },
    Auth::class => function () {
        return new Auth(new PDO("mysql:dbname=test;host=localhost", 'root', ''));
    }
]);
$container = $containerBuilder->build();

// Роутиинг
$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', ["App\controllers\HomeController", "index"]);
    $r->addRoute('GET', '/show/{id:\d+}', ["App\controllers\HomeController", "show"]);
    $r->addRoute('GET', '/edit/{id:\d+}', ["App\controllers\HomeController", "edit"]);
    $r->addRoute('POST', '/update/{id:\d+}', ["App\controllers\HomeController", "update"]);
    $r->addRoute('GET', '/create', ["App\controllers\HomeController", "create"]);
    $r->addRoute('POST', '/store', ["App\controllers\HomeController", "store"]);
    $r->addRoute('GET', '/delete/{id:\d+}', ["App\controllers\HomeController", "delete"]);
    $r->addRoute('GET', '/login', ["App\controllers\HomeController", "loginForm"]);
    $r->addRoute('POST', '/loginuser', ["App\controllers\HomeController", "loginUser"]);
    $r->addRoute('GET', '/registration', ["App\controllers\HomeController", "registrationForm"]);
    $r->addRoute('POST', '/registrationuser', ["App\controllers\HomeController", "registrationUser"]);
    $r->addRoute('GET', '/logout', ["App\controllers\HomeController", "logout"]);

});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        echo '404 Not Found';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        echo '405 Method Not Allowed';
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        $dataBase = new PDO("mysql:dbname=test;host=localhost", 'root', '');
        $auth = new Delight\Auth\Auth($dataBase);

        // Проверяю аутентификацию пользователя
        if (!$auth->check() and $handler[1] !== 'loginUser' and $handler[1] !== 'registrationForm' and $handler[1] !== 'registrationUser') {
            $container->call(["App\controllers\HomeController", "loginForm"]);
        } else {
            $container->call($handler, $vars);
        }
        break;
}
