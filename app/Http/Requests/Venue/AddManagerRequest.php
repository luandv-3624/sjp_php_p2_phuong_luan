<?php

namespace App\Http\Requests\Venue;

use Illuminate\Foundation\Http\FormRequest;

class AddManagerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_ids'   => ['required', 'array', 'min:1'],
            'user_ids.*' => ['distinct', 'integer', 'exists:users,id'],
        ];
    }
}
