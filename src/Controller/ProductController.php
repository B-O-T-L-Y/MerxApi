<?php

namespace App\Controller;

use App\DTO\CreateProductDTO;
use App\Service\ProductService;
use App\Validation\Validator;
use Doctrine\DBAL\Exception;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;

class ProductController
{
    public function __construct(private readonly ProductService $service)
    {
    }

    /**
     * @param ServerRequestInterface $request
     * @return Response
     */
    public function create(ServerRequestInterface $request): Response
    {
        $data = json_decode($request->getBody()->getContents(), true) ?? [];
        $dto = new CreateProductDTO($data);

        Validator::validate($dto);

        $product = $this->service->create((array)$dto);

        return new Response(
            201,
            ['Content-Type' => 'application/json'],
            json_encode((array)$product, JSON_UNESCAPED_UNICODE)
        );
    }

    /**
     * @throws Exception
     */
    public function show(ServerRequestInterface $request, array $args): Response
    {
        $product = $this->service->find($args['id']);

        return $product
            ? new Response(
                200,
                ['Content-Type' => 'application/json'],
                json_encode((array)$product)
            )
            : new Response(404);
    }

    /**
     * @throws Exception
     */
    public function delete(ServerRequestInterface $request, array $args): Response
    {
        $this->service->delete($args['id']);

        return new Response(204);
    }

    /**
     * @throws Exception
     */
    public function update(ServerRequestInterface $request, array $args): Response
    {
        $data = json_decode($request->getBody()->getContents(), true);
        $this->service->update($args['id'], $data);

        return new Response(204);
    }

    /**
     * @throws Exception
     */
    public function index(ServerRequestInterface $request): Response
    {
        $params = $request->getQueryParams();
        $products = $this->service->filter($params['category'] ?? null, $params['price'] ?? null);

        return new Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode(array_map(fn($p) => (array)$p, $products))
        );
    }
}