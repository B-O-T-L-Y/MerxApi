<?php

require __DIR__ . '/../vendor/autoload.php';

use Doctrine\DBAL\DriverManager;
use Ramsey\Uuid\Uuid;

try {
    $connection = DriverManager::getConnection([
        'dbname' => 'app',
        'user' => 'app_user',
        'password' => 'secret',
        'host' => 'db',
        'driver' => 'pdo_pgsql',
    ]);

    $connection->insert('products', [
        'id' => Uuid::uuid4()->toString(),
        'name' => 'Iphone 15',
        'price' => 999.9,
        'category' => 'electronics',
        'attributes' => json_encode(['brand' => 'Apple', 'color' => 'black']),
        'created_at' => new DateTimeImmutable()->format(DATE_ATOM),
    ]);

    $connection->insert('products', [
        'id' => Uuid::uuid4()->toString(),
        'name' => 'Laptop',
        'price' => 999.99,
        'category' => 'electronics',
        'attributes' => json_encode(['brand' => 'Lenovo', 'ram' => '16GB']),
        'created_at' => new DateTimeImmutable()->format(DATE_ATOM),
    ]);

    $connection->insert('products', [
        'id' => Uuid::uuid4()->toString(),
        'name' => 'Sneakers',
        'price' => 120.50,
        'category' => 'sportswear',
        'attributes' => json_encode(['brand' => 'Nike', 'size' => 42]),
        'created_at' => new DateTimeImmutable()->format(DATE_ATOM),
    ]);

    echo "✅ Seeding done\n";
} catch (\Doctrine\DBAL\Exception $e) {
    echo "❌ Seeding not done\n";
    echo $e->getMessage();
}