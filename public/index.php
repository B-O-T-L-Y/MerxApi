<?php

use App\Controller\ProductController;
use App\Repository\ProductRepository;
use App\Service\ProductService;
use App\Middleware\ErrorHandlerMiddleware;
use App\Routing\RouteRequestHandler;
use DI\Container;
use FastRoute\RouteCollector;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use function FastRoute\simpleDispatcher;

require __DIR__ . '/../vendor/autoload.php';

try {
    $container = new Container();

    $container->set(ProductRepository::class, new ProductRepository());
    $container->set(ProductService::class, new ProductService($container->get(ProductRepository::class)));
    $container->set(ProductController::class, new ProductController($container->get(ProductService::class)));

    $psr17Factory = new Psr17Factory();
    $creator = new ServerRequestCreator(
        $psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory
    );
    $request = $creator->fromGlobals();

    $dispatcher = simpleDispatcher(function (RouteCollector $route) {
        $route->addRoute('POST', '/products', [ProductController::class, 'create']);
        $route->addRoute('GET', '/products/{id}', [ProductController::class, 'show']);
        $route->addRoute('PATCH', '/products/{id}', [ProductController::class, 'update']);
        $route->addRoute('DELETE', '/products/{id}', [ProductController::class, 'delete']);
        $route->addRoute('GET', '/products', [ProductController::class, 'index']);
    });

    $errorHandler = new ErrorHandlerMiddleware();
    $routeHandler = new RouteRequestHandler($dispatcher, $container, $psr17Factory);

    $response = $errorHandler->process($request, $routeHandler);

    new SapiEmitter()->emit($response);
} catch (\Throwable $e) {
    echo $e->getMessage();
}
