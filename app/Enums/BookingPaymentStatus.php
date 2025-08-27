<?php

namespace App\Enums;

enum BookingPaymentStatus: string
{
    case UNPAID = 'unpaid';
    case PARTIAL = 'partial';
    case PAID = 'paid';

    public static function values(): array
    {
        return array_map(fn($s) => $s->value, self::cases());
    }
}
