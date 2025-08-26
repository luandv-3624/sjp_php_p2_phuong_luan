<?php

namespace App\Enums;

enum SortOrder: string
{
    case ASC = 'asc';
    case DESC = 'desc';

    public static function values(): array
    {
        return array_map(fn ($s) => $s->value, self::cases());
    }
}
