<?php

namespace App\Http\Requests\GlobalConfiguration;

use App\Helpers\SubdomainHelper;
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
        $portal = SubdomainHelper::getPortal($this);

        return [
            'name' => 'required|string',
            'subdomain' => 'required|string|unique:portals,subdomain,' . $portal->id,
            'primary_color' => 'required|string',
            'secondary_color' => 'required|string',
            'btn_text_color' => 'required|string',
            'menu_text_color' => 'required|string',
            'keys' => 'required|array',
            'keys.*' => 'required|string|exists:global_configurations,key',
            'values' => 'required|array',
            'values.*' => 'required'
        ];
    }
}
