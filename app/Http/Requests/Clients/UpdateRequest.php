<?php

namespace App\Http\Requests\Clients;

use App\Models\Task;
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
            'name' => 'required|string|max:60',
            'active' => 'required|boolean',
            'company_id' => 'nullable|exists:companies,id',
            'email' => 'nullable|email|unique:clients,email,' . $this->route('client')->id . ',id',
            'phone' => 'nullable|string',
            'vat_number' => 'nullable|string',
            'language_id' => 'nullable|exists:languages,id',
            'currency_id' => 'required|exists:currencies,id',
            'address' => 'nullable|string|max:250',
            'city' => 'nullable|string|max:50',
            'zip' => 'nullable|string|max:10',
            'country_id' => 'nullable|exists:countries,id',
            'state' => 'nullable|string|max:50',
            'account_number' => 'nullable|string'
        ];
    }
}
