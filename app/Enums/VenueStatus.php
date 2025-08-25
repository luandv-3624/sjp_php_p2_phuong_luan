<?php

namespace App\Enums;

enum VenueStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case BLOCKED = 'blocked';

    public static function values(): array
    {
        return array_map(fn ($s) => $s->value, self::cases());
    }
}
