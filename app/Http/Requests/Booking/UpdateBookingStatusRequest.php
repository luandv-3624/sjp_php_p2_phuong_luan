<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\BookingStatus;
use Illuminate\Support\Facades\Gate;

class UpdateBookingStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        $booking   = $this->route('booking');
        $newStatus = $this->input('status');

        return Gate::allows('updateStatus', [$booking, $newStatus]);
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                'string',
                Rule::in(BookingStatus::values()),
            ],
        ];
    }
}
