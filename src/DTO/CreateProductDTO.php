<?php

namespace App\DTO;

use App\Validation\NotBlank;
use App\Validation\Type;

class CreateProductDTO
{
    #[NotBlank]
    #[Type('string')]
    public readonly string $name;

    #[Type('float')]
    public readonly float $price;

    #[Type('string')]
    public readonly string $category;

    public readonly array $attributes;

    public function __construct(array $data)
    {
        $this->name = $data['name'] ?? '';
        $this->price = (float)($data['price']) ?? 0;
        $this->category = $data['category'] ?? '';
        $this->attributes = $data['attributes'] ?? [];
    }
}