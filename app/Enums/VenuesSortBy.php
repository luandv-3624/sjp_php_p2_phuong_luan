<?php

namespace App\Enums;

enum VenuesSortBy: string
{
    case ID = 'id';
    case NAME = 'name';
    case STATUS = 'status';
    case CREATED_AT = 'created_at';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
