<?php

namespace App\Http\Requests\UserInventories;

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
            'user_id' => 'required|int|exists:users,id',
            'inventory_id' => 'required|int|exists:inventories,id',
            'quantity' => 'required|numeric',
            'extract_date' => 'nullable|date',
            'return_date' => 'nullable|date',
        ];
    }
}
