<?php

namespace App\Http\Requests\UserInventories;

use App\Models\Inventory;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{



    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $inventory = Inventory::find($this->request->get('inventory_id'));
        return [
            'user_id' => 'required|int|exists:users,id',
            'inventory_id' => 'required|int|exists:inventories,id',
            'quantity' => 'required|numeric|max:'.$inventory->remaining_stock,
            'extract_date' => 'nullable|date',
            'return_date' => 'nullable|date',
        ];
    }
}
