<?php

namespace OrderProcessorCore\Domain\Enums;

enum OrderStatus: int {
    case Cancelled = 0;
    case Pending = 1;
    case Processing = 2;
    case Completed = 3;

    public static function map(mixed $value): self {
        if (is_int($value)) {
            return self::fromInt($value);
        }

        if (is_string($value)) {
            return self::fromString($value);
        }

        throw new \InvalidArgumentException("Invalid order status value: " . json_encode($value));
    }

    private static function fromInt(int $value): self {
        return match ($value) {
            0 => self::Cancelled,
            1 => self::Pending,
            2 => self::Processing,
            3 => self::Completed,
            default => throw new \InvalidArgumentException("Invalid order status value: $value"),
        };
    }

    private static function fromString(string $value): self {
        return match (strtolower($value)) {
            'cancelled' => self::Cancelled,
            'pending' => self::Pending,
            'processing' => self::Processing,
            'completed' => self::Completed,
            default => throw new \InvalidArgumentException("Invalid order status value: $value"),
        };
    }
}