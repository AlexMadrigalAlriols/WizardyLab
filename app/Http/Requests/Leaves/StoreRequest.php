<?php

namespace App\Http\Requests\Leaves;

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
            'name' => 'required|string',
            'type' => 'required|string',
            'date' => 'required|date',
            'reason' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'duration' => 'required|string'
        ];
    }
}
