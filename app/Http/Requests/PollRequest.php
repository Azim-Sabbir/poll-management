<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PollRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'question' => 'required|string|max:255',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'question.required' => 'The question field is required.',
            'question.max' => 'The question must not exceed 255 characters.',
            'options.required' => 'At least two options are required.',
            'options.min' => 'At least two options are required.',
            'options.*.required' => 'Each option field is required.',
            'options.*.max' => 'Each option must not exceed 255 characters.',
        ];
    }
}
