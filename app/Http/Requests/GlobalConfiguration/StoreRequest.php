<?php

namespace App\Http\Requests\GlobalConfiguration;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'keys' => 'required|array',
            'keys.*' => 'required|string|exists:global_configurations,key',
            'values' => 'required|array',
            'values.*' => 'required'
        ];
    }
}
