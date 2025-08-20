<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

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
                'string',
                'in:' . implode(',', array_column(\App\Enums\UsersSortBy::cases(), 'value')),
            ],
            'sortOrder' => [
                'sometimes',
                'string',
                'in:' . implode(',', array_column(\App\Enums\SortOrder::cases(), 'value')),
            ],
            'perPage'   => 'sometimes|integer|min:1|max:100',
            'search'    => 'sometimes|string|max:255',
        ];
    }
}
