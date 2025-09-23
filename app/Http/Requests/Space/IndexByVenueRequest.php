<?php

namespace App\Http\Requests\Space;

use App\Enums\SortOrder;
use App\Enums\SpacesSortBy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexByVenueRequest extends FormRequest
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
            'pageSize' => ['sometimes', 'integer', 'min:1'],
            'name' => ['sometimes', 'string', 'max:255'],
            'spaceTypeId' => ['sometimes', 'integer', 'exists:space_types,id'],
            'priceTypeId' => ['sometimes', 'integer', 'exists:price_types,id'],
        ];
    }
}
