<?php

namespace App\Http\Requests\StockMovements;

use App\Models\StockMovement;
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
            'quantity' => 'required|numeric',
            'type' => 'required|in:' . implode(',', StockMovement::TYPES),
            'reason' => 'nullable|string',
        ];
    }
}
