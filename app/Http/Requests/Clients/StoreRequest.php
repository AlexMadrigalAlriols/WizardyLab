<?php

namespace App\Http\Requests\Clients;

use App\Models\Task;
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
            'active' => 'required|boolean',
            'company_id' => 'nullable|exists:companies,id',
            'email' => 'nullable|email|unique:clients,email',
            'phone' => 'nullable|string',
            'vat_number' => 'nullable|string',
            'language_id' => 'nullable|exists:languages,id',
            'currency_id' => 'required|exists:currencies,id',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'zip_code' => 'nullable|string',
            'country_id' => 'nullable|exists:countries,id',
            'state' => 'nullable|string',
        ];
    }
}
