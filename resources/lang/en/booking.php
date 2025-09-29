<?php

return [
    'space_not_found' => 'The selected space could not be found.',
    'space_unavailable' => 'The space is currently unavailable for booking.',
    'invalid_time' => 'The start and end times are invalid.',
    'space_already_booked' => 'The space is already booked for the selected time period.',
    'create_success' => 'Booking created successfully.',
    'booking_not_found' => 'The requested booking could not be found.',
    'venue_not_approved' => 'The venue has not been approved yet.',
    'hello'           => 'Hello',
    'thank_you'       => 'Thank you',
    'mail_subject'    => 'Booking update notification',
    'mail_confirmed'  => 'Your booking for ":space" has been confirmed. Please proceed with the payment.',
    'mail_rejected'   => 'We are sorry, but your booking for ":space" has been rejected.',
    'mail_accepted'   => 'Your payment for the booking ":space" has been accepted. You can check in at the venue.',
    'status_updated'  => 'Booking status updated successfully.',
    'cannot_cancel_in_status' => 'The booking is already in :status status, so you cannot cancel it. Please contact the space administrator for support.',
    'must_be_accepted_to_checkin' => 'Booking must be in ACCEPTED status to check in.',
    'already_checked_in'          => 'You have already checked in.',
    'invalid_checkin_time'        => 'Check-in time must be within the booking period.',
    'checkin_success'             => 'Checked in successfully.',
    'must_be_accepted_to_checkout' => 'Booking must be in ACCEPTED status to check out.',
    'must_checkin_first'           => 'You must check in before checking out.',
    'already_checked_out'          => 'You have already checked out.',
    'invalid_checkout_time'        => 'Check-out time must be between check-in time and end time.',
    'checkout_success'             => 'Checked out successfully.',
    'notification' => [
        'confirmed_unpaid' => [
            'title' => 'Booking confirmed',
            'message' => 'Your booking #:id has been confirmed. Please complete the payment.',
        ],
        'rejected' => [
            'title' => 'Booking rejected',
            'message' => 'Your booking #:id has been rejected. Please contact support for more details.',
        ],
        'accepted' => [
            'title' => 'Booking accepted',
            'message' => 'Your booking #:id has been accepted. We look forward to serving you.',
        ],
    ],
];
