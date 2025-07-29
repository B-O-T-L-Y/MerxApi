<?php

namespace App\Validation;

use Attribute;

#[Attribute]
class Type
{
    public function __construct(public string $type)
    {
    }
}