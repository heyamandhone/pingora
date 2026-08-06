<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConversationStoreRequest extends FormRequest
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

            'participant_ids' => [
                'required',
                'array',
                'min:1',
            ],

            'participant_ids.*' => [
                'exists:users,id',
                'distinct',
            ],
        ];
    }

    /**
     * Validation messages.
     */
    public function messages(): array
    {
        return [
            'participant_ids.required' => 'Please select at least one participant.',

            'participant_ids.min' => 'Please select at least one participant.',

            'name.required_if' => 'Group name is required.',
        ];
    }
}
