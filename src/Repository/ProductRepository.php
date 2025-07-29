<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;

class ProductRepository
{
    private $connection;

    public function __construct()
    {
        $this->connection = DriverManager::getConnection([
            'dbname' => 'app',
            'user' => 'app_user',
            'password' => 'secret',
            'host' => 'db',
            'driver' => 'pdo_pgsql',
        ]);
    }

    public function save(Product $product): void
    {
        $this->connection->insert('products', [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'category' => $product->category,
            'attributes' => json_encode($product->attributes),
            'created_at' => $product->createdAt,
        ]);
    }

    /**
     * @throws Exception
     */
    public function find(string $id): ?Product
    {
        $data = $this->connection->fetchAssociative(/** @lang PostgreSQL */ 'SELECT * FROM products WHERE id = ?', [$id]);

        if (!$data) return null;

        return new Product(
            $data['id'],
            $data['name'],
            $data['price'],
            $data['category'],
            json_decode($data['attributes'], true),
            $data['created_at']
        );
    }

    /**
     * @throws Exception
     */
    public function delete(string $id): void
    {
        $this->connection->delete('products', ['id' => $id]);
    }

    /**
     * @throws Exception
     */
    public function update(string $id, array $fields): void
    {
        if (isset($fields['attributes'])) {
            $fields['attributes'] = json_encode($fields['attributes']);
        }

        $this->connection->update('products', $fields, ['id' => $id]);
    }

    /**
     * @throws Exception
     */
    public function filter(?string $category, ?float $price): array
    {
        $db = $this->connection->createQueryBuilder()->select('*')->from('products');

        if ($category) $db->andWhere('category = :category')->setParameter('category', $category);
        if ($price) $db->andWhere('price <= :price')->setParameter('price', $price);

        $results = $db->executeQuery()->fetchAllAssociative();

        return array_map(fn($d) => new Product(
            $d['id'],
            $d['name'],
            $d['price'],
            $d['category'],
            json_decode($d['attributes'], true),
            $d['created_at'],
        ), $results);
    }
}