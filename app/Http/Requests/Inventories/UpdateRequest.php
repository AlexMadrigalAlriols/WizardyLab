<?php

namespace App\Http\Requests\Inventories;

use App\Models\Inventory;
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
            'name' => 'required|string',
            'reference' => 'required|string',
            'stock' => 'required|numeric',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'shop_place' => 'nullable|string',
        ];
    }
}