<?php

namespace App\Http\Requests\BoardRules;

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
            'trigger' => 'required|array',
            'actions' => 'required|array',
            'actions.*.type' => 'required|string',
            'actions.*.name' => 'required|string',
            'actions.*.prefix' => 'nullable|string',
            'actions.*.suffix' => 'nullable|string',
            'actions.*.description' => 'nullable|string',
            'actions.*.options' => 'nullable|array',
            'actions.*.options.id' => 'nullable|string',
            'actions.*.options.items' => 'nullable|array',
            'actions.*.options.items.*' => 'nullable|string',
            'actions.*.options.value' => 'nullable|string',
            'actions.*.sentence' => 'nullable|string',
        ];
    }
}
