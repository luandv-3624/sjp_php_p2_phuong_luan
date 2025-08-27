<?php

namespace App\Enums;

enum BookingStatus: string
{
    case PENDING = 'pending';
    case CONFIRMED_UNPAID = 'confirmed-unpaid';
    case PAID_PENDING = 'paid-pending';
    case PARTIAL_PENDING = 'partial-pending';
    case ACCEPTED = 'accepted';
    case DONE = 'done';
    case REJECTED = 'rejected';
    case CANCELLED = 'cancelled';

    public static function values(): array
    {
        return array_map(fn($s) => $s->value, self::cases());
    }
}
