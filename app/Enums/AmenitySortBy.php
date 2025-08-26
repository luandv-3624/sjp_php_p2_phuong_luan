<?php

namespace App\Enums;

enum AmenitySortBy: string
{
    case NAME = 'name';
    case CODE = 'code';

    public static function values(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }
}
