<?php

namespace App\Enums;

enum SpaceStatus: string
{
    case AVAILABLE = 'available';
    case UNAVAILABLE = 'unavailable';

    public static function values(): array
    {
        return array_map(fn($s) => $s->value, self::cases());
    }
}
