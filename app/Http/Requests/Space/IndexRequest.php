<?php

namespace App\Http\Requests\Space;

use App\Enums\SortOrder;
use App\Enums\SpacesSortBy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexRequest extends FormRequest
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
            'sortBy' => [
                'sometimes',
                Rule::in(SpacesSortBy::values()),
            ],
            'sortOrder' => [
                'sometimes',
                Rule::in(SortOrder::values()),
            ],
            'pageSize' => ['sometimes', 'integer', 'min:1'],

            'venueId' => ['sometimes', 'integer', 'exists:venues,id'],
            'wardId' => ['sometimes', 'integer', 'exists:wards,id'],
            'provinceId' => ['sometimes', 'integer', 'exists:provinces,id'],

            'name' => ['sometimes', 'string', 'max:255'],

            'spaceTypeId' => ['sometimes', 'integer', 'exists:space_types,id'],
            'priceTypeId' => ['sometimes', 'integer', 'exists:price_types,id'],

            'minCapacity' => ['sometimes', 'integer', 'min:0'],
            'maxCapacity' => ['sometimes', 'integer', 'min:0'],

            'minPrice' => ['sometimes', 'numeric', 'min:0'],
            'maxPrice' => ['sometimes', 'numeric', 'min:0'],

            'startTime' => ['sometimes', 'date'],
            'endTime' => ['sometimes', 'date'],
        ];
    }
}
