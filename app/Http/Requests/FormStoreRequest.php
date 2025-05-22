<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormStoreRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'method' => 'required|string|max:255',
            'action' => 'required|string|max:255',
            'fields' => 'required|array',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The form title is required.',
            'title.max' => 'The form title cannot exceed 255 characters.',
            'method.required' => 'The form method is required.',
            'method.max' => 'The form method cannot exceed 255 characters.',
            'action.required' => 'The form action URL is required.',
            'action.max' => 'The form action URL cannot exceed 255 characters.',
            'fields.required' => 'At least one field is required for the form.',
            'fields.array' => 'The form fields must be provided as an array.',
        ];
    }
}
