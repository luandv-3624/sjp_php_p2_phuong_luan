<?php

namespace App\Http\Requests\Space;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'name' => ['sometimes', 'string', 'max:255'],
            'space_type_id' => ['sometimes', 'exists:space_types,id'],
            'capacity' => ['sometimes', 'integer', 'min:1'],
            'price_type_id' => ['sometimes', 'exists:price_types,id'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'description' => ['sometimes', 'string'],
            'status' => ['sometimes', 'in:available,unavailable'],
        ];
    }
}
