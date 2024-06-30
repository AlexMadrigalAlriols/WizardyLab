<?php

namespace App\Http\Requests\DeliveryNotes;

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
            'issue_date' => 'required|date',
            'generate_invoice' => 'required|boolean',
            'substract_stock' => 'nullable|boolean',
            'notes' => 'nullable|string',
            'type' => 'required|string|in:valued,non-valued',
            'client_id' => 'required|exists:clients,id',
            'amount' => 'nullable|numeric',
            'items' => 'nullable|array'
        ];
    }
}
