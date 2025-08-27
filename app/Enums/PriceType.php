<?php

namespace App\Enums;

enum PriceType: string
{
    case MONTH = 'month';
    case DAY = 'day';
    case HOUR = 'hour';

    public static function values(): array
    {
        return array_map(fn($s) => $s->value, self::cases());
    }
}
