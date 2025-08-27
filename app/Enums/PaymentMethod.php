<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case MOMO = 'momo';
    case VNPAY = 'vnpay';

    public static function values(): array
    {
        return array_map(fn ($s) => $s->value, self::cases());
    }
}
