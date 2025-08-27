<?php

namespace App\Enums;

enum SpacesSortBy: string
{
    case VENUE_ID = 'venue_id';
    case NAME = 'name';
    case SPACE_TYPE_ID = 'space_type_id';
    case CAPACITY = 'capacity';
    case PRICE_TYPE_ID = 'price_type_id';
    case PRICE = 'price';
    case STATUS = 'status';
    case CREATED_AT = 'created_at';
    case UPDATED_AT = 'updated_at';

    public static function values(): array
    {
        return array_map(fn($s) => $s->value, self::cases());
    }
}
