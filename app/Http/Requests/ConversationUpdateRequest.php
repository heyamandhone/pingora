<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConversationUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [
            'type' => [
                'required',
                Rule::in([
                    'private',
                    'group',
                ]),
            ],

            'name' => [
                'nullable',
                'string',
                'max:255',
                'required_if:type,group',
            ],
        ];
    }

    /**
     * Validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required_if' => 'Group name is required.',
        ];
    }
}
