<?php

namespace App\Http\Requests\Projects;

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
            'code' => 'required|string|unique:projects,code',
            'client_id' => 'nullable|exists:clients,id',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'limit_hours' => 'nullable|numeric',
            'status' => 'required|exists:statuses,id',
            'description' => 'nullable|string',
            'users' => 'array',
            'users.*' => 'exists:users,id',
            'departments' => 'array',
            'departments.*' => 'exists:departments,id',
        ];
    }
}
