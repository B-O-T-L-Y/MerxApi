<?php

namespace App\Routing;

use DI\Container;
use FastRoute\Dispatcher;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RouteRequestHandler implements RequestHandlerInterface
{
    public function __construct(
        private Dispatcher   $dispatcher,
        private Container    $container,
        private Psr17Factory $psr17Factory
    )
    {
    }


    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $routeInfo = $this->dispatcher->dispatch(
            $request->getMethod(),
            rawurldecode($request->getUri()->getPath())
        );

        return match ($routeInfo[0]) {
            Dispatcher::NOT_FOUND =>
            $this->psr17Factory->createResponse(404)
                ->withBody($this->psr17Factory->createStream(
                    json_encode(['error' => 'Resource not found'])
                ))->withHeader('Content-Type', 'application/json'),

            Dispatcher::METHOD_NOT_ALLOWED =>
            $this->psr17Factory->createResponse(405)
                ->withBody($this->psr17Factory->createStream(
                    json_encode(['error' => 'Method not allowed'])
                ))->withHeader('Content-Type', 'application/json'),

            Dispatcher::FOUND => (function () use ($routeInfo, $request) {
                [$class, $method] = $routeInfo[1];
                $vars = $routeInfo[2];
                $controller = $this->container->get($class);
                return $controller->$method($request, $vars);
            })(),

            default => throw new \RuntimeException('Unexpected routing state'),
        };
    }
}