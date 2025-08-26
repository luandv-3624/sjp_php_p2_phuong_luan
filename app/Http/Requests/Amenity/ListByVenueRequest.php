<?php

namespace App\Http\Requests\Amenity;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\SortOrder;
use App\Enums\AmenitySortBy;

class ListByVenueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sortBy' => [
                'nullable',
                'string',
                Rule::in(AmenitySortBy::values()),
            ],
            'sortOrder' => [
                'nullable',
                'string',
                Rule::in(SortOrder::values()),
            ],
            'search'   => 'nullable|string|max:255',
            'code'     => 'nullable|string|max:50',
            'pageSize'  => 'nullable|integer|min:1|max:100',
        ];
    }
}
