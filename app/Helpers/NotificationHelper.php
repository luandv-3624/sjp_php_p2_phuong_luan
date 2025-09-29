<?php

namespace App\Helpers;

use App\Enums\BookingStatus;

class NotificationHelper
{
    public static function getBookingNotificationText(string $status, int $bookingId): array
    {
        switch ($status) {
            case BookingStatus::CONFIRMED_UNPAID->value:
                return [
                    __("booking.notification.confirmed_unpaid.title"),
                    __("booking.notification.confirmed_unpaid.message", ['id' => $bookingId]),
                ];

            case BookingStatus::REJECTED->value:
                return [
                    __("booking.notification.rejected.title"),
                    __("booking.notification.rejected.message", ['id' => $bookingId]),
                ];

            case BookingStatus::ACCEPTED->value:
                return [
                    __("booking.notification.accepted.title"),
                    __("booking.notification.accepted.message", ['id' => $bookingId]),
                ];

            default:
                return [
                    __("booking.notification.default.title"),
                    __("booking.notification.default.message", ['id' => $bookingId]),
                ];
        }
    }
}
