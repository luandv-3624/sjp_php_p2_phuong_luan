<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use App\Models\Booking;
use App\Enums\BookingStatus;

class BookingStatusChangedMail extends Mailable
{
    public Booking $booking;
    public string $newStatus;

    public function __construct(Booking $booking, string $newStatus)
    {
        $this->booking   = $booking;
        $this->newStatus = $newStatus;
    }

    public function build()
    {
        $subject = match ($this->newStatus) {
            BookingStatus::CONFIRMED_UNPAID->value => __('booking.mail_confirmed', [
                'space' => $this->booking->space->name,
            ]),
            BookingStatus::REJECTED->value => __('booking.mail_rejected', [
                'space' => $this->booking->space->name,
            ]),
            BookingStatus::ACCEPTED->value => __('booking.mail_accepted', [
                'space' => $this->booking->space->name,
            ]),
            default => __('booking.mail_subject'),
        };

        return $this->subject($subject)
            ->view('emails.booking-status-changed')
            ->with([
                'booking'   => $this->booking,
                'newStatus' => $this->newStatus,
                'subject'   => $subject,
            ]);
    }
}
