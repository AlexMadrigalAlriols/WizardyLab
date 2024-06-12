<?php

namespace App\Http\Requests\SearchListOption;

use Illuminate\Foundation\Http\FormRequest;

class InvokeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'search' => 'nullable|string',
            'model' => 'required|string',
            'field' => 'required|string',
            'method' => 'nullable|string',
        ];
    }
}
