<?php

namespace App\Http\Requests\Booking;

use App\Enums\BookingPaymentStatus;
use App\Enums\BookingsSortBy;
use App\Enums\BookingStatus;
use App\Enums\SortOrder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexByUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'spaceId' => 'sometimes|integer|exists:spaces,id',

            'status' => [
                'sometimes',
                'string',
                Rule::in(BookingStatus::values()),
            ],

            'statusPayment' => [
                'sometimes',
                'string',
                Rule::in(BookingPaymentStatus::values()),
            ],

            'startTime' => 'sometimes|date',
            'endTime' => [
                'sometimes',
                'date',
                function ($attribute, $value, $fail) {
                    $startTime = $this->input('startTime');
                    if ($startTime && strtotime($value) <= strtotime($startTime)) {
                        $fail('The endTime must be after startTime.');
                    }
                },
            ],

            'sortBy' => [
                'sometimes',
                'string',
                Rule::in(BookingsSortBy::values()),
            ],

            'sortOrder' => [
                'sometimes',
                'string',
                Rule::in(SortOrder::values()),
            ],

            'pageSize' => 'sometimes|integer|min:1|max:100',
        ];
    }
}
