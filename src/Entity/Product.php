<?php

namespace App\Entity;

class Product
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly float $price,
        public readonly string $category,
        public readonly array $attributes,
        public readonly string $createdAt,
    )
    {
    }
}