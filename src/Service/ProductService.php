<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Exception;
use Ramsey\Uuid\Uuid;

class ProductService
{
    public function __construct(private ProductRepository $repository)
    {
    }

    public function create(array $data): Product
    {
        $product = new Product(
            Uuid::uuid4()->toString(),
            $data['name'],
            $data['price'],
            $data['category'],
            $data['attributes'] ?? [],
            new DateTimeImmutable()->format(DATE_ATOM),
        );

        $this->repository->save($product);

        return $product;
    }

    /**
     * @throws Exception
     */
    public function find(string $id): ?Product
    {
        return $this->repository->find($id);
    }

    /**
     * @throws Exception
     */
    public function delete(string $id): void
    {
        $this->repository->delete($id);
    }

    /**
     * @throws Exception
     */
    public function update(string $id, array $data): void
    {
        $this->repository->update($id, $data);
    }

    /**
     * @throws Exception
     */
    public function filter(?string $category, ?float $price): array
    {
        return $this->repository->filter($category, $price);
    }
}