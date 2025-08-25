<?php

namespace App\Http\Requests\Venue;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVenueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => 'sometimes|string|max:255',
            'address'     => 'sometimes|string|max:500',
            'ward_id'     => 'sometimes|exists:wards,id',
            'lat'         => 'sometimes|numeric',
            'lng'         => 'sometimes|numeric',
            'description' => 'nullable|string',
        ];
    }
}
