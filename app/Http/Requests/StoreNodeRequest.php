<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNodeRequest extends FormRequest
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
                "existing_node_id" => "nullable|integer|exists:nodes,id",
                'number_of_parent' => 'required|integer|min:1',
                'number_of_children'=> 'required|min:0',
        ];
    }
    public function messages(): array
    {
        return [
            'existing_node_id.exists' => 'The specified existing node does not exist.',
        ];
    }
}
