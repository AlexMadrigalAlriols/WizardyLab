<?php

namespace App\Http\Requests\UserInventories;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'user_id' => 'required|int|exists:users,id',
            'extract_date' => 'nullable|date',
            'return_date' => 'nullable|date',
            'items' => 'required|array',
            'items.*.id' => 'required|string|exists:items,id',
            'items.*.qty' => 'required|numeric',
        ];
    }
}
