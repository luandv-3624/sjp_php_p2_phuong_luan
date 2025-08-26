<?php

namespace App\Http\Requests\Amenity;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAmenityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => [
                'required',
                'string',
                Rule::unique('amenities', 'code')
                        ->where(fn ($query) => $query->where('venue_id', $this->venue_id)),
            ],
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'venue_id'    => 'required|exists:venues,id',
        ];
    }
}
