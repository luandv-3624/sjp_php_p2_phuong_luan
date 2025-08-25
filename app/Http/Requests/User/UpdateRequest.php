<?php

namespace App\Http\Requests\User;

use App\Enums\AccountStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'status' => [
                'sometimes',
                'string',
                Rule::in(AccountStatus::values()),
            ],
            'role_id' => [
                'sometimes',
                'int',
                'exists:roles,id'
            ]
        ];
    }
}
