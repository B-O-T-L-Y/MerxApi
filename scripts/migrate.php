<?php

require __DIR__ . '/../vendor/autoload.php';
use Doctrine\DBAL\DriverManager;

try {
    $connection = DriverManager::getConnection([
        'dbname' => 'app',
        'user' => 'app_user',
        'password' => 'secret',
        'host' => 'db',
        'driver' => 'pdo_pgsql',
    ]);

    $connection->executeStatement(/** @lang PostgreSQL */ '
       CREATE TABLE IF NOT EXISTS products (
        id UUID PRIMARY KEY,
        name VARCHAR(255) UNIQUE,
        price NUMERIC(10,2),
        category VARCHAR(100),
        attributes JSON,
        created_at TIMESTAMPTZ DEFAULT NOW()
        ); 
    ');

    echo "✅ Migration done\n";
} catch (\Doctrine\DBAL\Exception $e) {
    echo "❌ Migration not done\n";
    echo $e->getMessage();
}
