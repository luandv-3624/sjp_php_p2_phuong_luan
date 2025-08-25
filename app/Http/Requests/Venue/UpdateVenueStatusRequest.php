<?php

namespace App\Http\Requests\Venue;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\VenueStatus;
use Illuminate\Validation\Rule;

class UpdateVenueStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(VenueStatus::values())],
        ];
    }
}
