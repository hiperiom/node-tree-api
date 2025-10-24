<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexNodeRequest extends FormRequest
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
            'depth' => ['nullable', 'integer', 'min:1', 'max:5'],
        ];
    }
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        parent::failedValidation($validator);
    }
}
