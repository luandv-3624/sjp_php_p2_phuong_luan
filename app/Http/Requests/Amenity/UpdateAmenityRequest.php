<?php

namespace App\Http\Requests\Amenity;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAmenityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $amenityId = $this->route('amenity')?->id;
        $venueId   = $this->venue_id ?? $this->route('amenity')?->venue_id;

        return [
            'code' => [
                'sometimes',
                'string',
                Rule::unique('amenities', 'code')
                        ->where(fn ($query) => $query->where('venue_id', $venueId))
                        ->ignore($amenityId),
            ],
            'name'        => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'venue_id'    => 'sometimes|exists:venues,id',
        ];
    }
}
