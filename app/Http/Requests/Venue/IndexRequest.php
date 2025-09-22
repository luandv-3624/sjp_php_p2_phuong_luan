<?php

namespace App\Http\Requests\Venue;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\VenueStatus;
use App\Enums\SortOrder;
use App\Enums\VenuesSortBy;

class IndexRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'ownerId' => 'sometimes|integer|exists:users,id',
            'wardId'  => 'sometimes|integer|exists:wards,id',
            'name' => 'sometimes|string|max:255',
            'address' => 'sometimes|string|max:255',

            'status' => [
                'sometimes',
                'string',
                Rule::in(VenueStatus::values()),
            ],

            'sortBy' => [
                'sometimes',
                'string',
                Rule::in(VenuesSortBy::values()), // ví dụ: id, name, status, created_at
            ],

            'sortOrder' => [
                'sometimes',
                'string',
                Rule::in(SortOrder::values()), // asc, desc
            ],

            'pageSize' => 'sometimes|integer|min:1|max:100',
        ];
    }
}
