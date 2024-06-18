<?php

namespace App\Http\Requests\Attendances;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date' => 'required|date',
            'check_in' => 'required|array',
            'check_out' => 'required|array',
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:attendances,id'
        ];
    }
}
