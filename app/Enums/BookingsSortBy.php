<?php

namespace App\Enums;

enum BookingsSortBy: string
{
    case USER_ID = 'user_id';
    case SPACE_ID = 'space_id';
    case STATUS = 'status';
    case STATUS_PAYMENT = 'status_payment';
    case TOTAL_PRICE = 'total_price';
    case CREATED_AT = 'created_at';
    case UPDATED_AT = 'updated_at';

    public static function values(): array
    {
        return array_map(fn($s) => $s->value, self::cases());
    }
}
