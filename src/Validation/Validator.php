<?php

namespace App\Validation;

use ReflectionClass;
use InvalidArgumentException;

class Validator
{
    public static function validate(object $dto): void
    {
        $refClass = new ReflectionClass($dto);

        foreach ($refClass->getProperties() as $property) {
            $value = $property->getValue($dto);
            $attributes = $property->getAttributes();

            foreach ($attributes as $attr) {
                $instance = $attr->newInstance();

                // NotBlank
                if ($instance instanceof NotBlank) {
                    if ($value === null || $value === '' || (is_array($value) && empty($value))) {
                        throw new InvalidArgumentException("Field '{$property->getName()}' should not be blank");
                    }
                }

                // Type
                if ($instance instanceof Type) {
                    $expected = $instance->type;
                    if (!self::isOfType($value, $expected)) {
                        throw new InvalidArgumentException(
                            "Field '{$property->getName()}' must be of type {$expected}, " . gettype($value) . " given"
                        );
                    }
                }
            }
        }
    }

    private static function isOfType(mixed $value, string $expected): bool
    {
        return match ($expected) {
            'string' => is_string($value),
            'int', 'integer' => is_int($value),
            'float', 'double' => is_float($value),
            'array' => is_array($value),
            'bool', 'boolean' => is_bool($value),
            default => $value instanceof $expected,
        };
    }
}
