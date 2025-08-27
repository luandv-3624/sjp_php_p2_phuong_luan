<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case SUCCESS = 'success';
    case FAILED = 'failed';
    case REFUNDED = 'refunded';

    public static function values(): array
    {
        return array_map(fn ($s) => $s->value, self::cases());
    }
}
