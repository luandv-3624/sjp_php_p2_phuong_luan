<?php

namespace App\Http\Requests\Venue;

use Illuminate\Foundation\Http\FormRequest;

class CreateVenueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'address'     => 'required|string|max:500',
            'ward_id'     => 'required|exists:wards,id',
            'lat'         => 'required|numeric',
            'lng'         => 'required|numeric',
            'description' => 'nullable|string',
        ];
    }
}
