<?php

namespace App\Enums;

enum AccountStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case VERIFIED = 'verified';

    public static function values(): array
    {
        return array_map(fn($s) => $s->value, self::cases());
    }
}
