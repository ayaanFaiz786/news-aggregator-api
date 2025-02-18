<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SetPreferencesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'sources' => 'array',
            'sources.*' => 'string',
            'categories' => 'array',
            'categories.*' => 'string',
            'authors' => 'array',
            'authors.*' => 'string',
        ];
    }

    /**
     * @description Custom error message
     * @return array
     */
    public function messages()
    {
        return [
            'sources.array' => 'The sources field must be an array.',
            'sources.*.string' => 'Each source must be a valid string.',
            'categories.array' => 'The categories field must be an array.',
            'categories.*.string' => 'Each category must be a valid string.',
            'authors.array' => 'The authors field must be an array.',
            'authors.*.string' => 'Each author must be a valid string.',
        ];
    }
}
