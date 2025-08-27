<?php

namespace App\Enums;

enum Language: string
{
    case EN = 'en';
    case VI = 'vi';

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}
