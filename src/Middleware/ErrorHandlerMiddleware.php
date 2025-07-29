<?php

namespace App\Middleware;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ErrorHandlerMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (\Throwable $e) {
            [$status, $message] = $this->mapException($e);

            return new Response(
                $status,
                ['Content-Type' => 'application/json'],
                json_encode(['error' => $message], JSON_UNESCAPED_UNICODE)
            );
        }
    }

    private function mapException(\Throwable $e): array
    {
        return match (true) {
            $e instanceof UniqueConstraintViolationException => [409, $this->mapUniqueConstraintMessage($e)],
            $e instanceof \InvalidArgumentException => [422, $e->getMessage()],
            $e instanceof \RuntimeException && str_contains($e->getMessage(), 'not found') => [404, $e->getMessage()],
            default => [500, 'Internal server error'],
        };
    }

    private function mapUniqueConstraintMessage(UniqueConstraintViolationException $e): string
    {
        if (preg_match('/Key \((.+)\)=\((.+)\)/', $e->getMessage(), $m)) {
            return ucfirst($m[1]) . " '{$m[2]}' already exists";
        }
        return 'Duplicate entry';
    }
}
