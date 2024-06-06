<?php

namespace App\Http\Requests\Projects;

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
            'name' => 'required|string|max:50',
            'client_id' => 'nullable|exists:clients,id',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'limit_hours' => 'nullable|numeric',
            'status' => 'required|exists:statuses,id',
            'description' => 'nullable|string|max:1000',
            'users' => 'nullable|array',
            'users.*' => 'nullable|exists:users,id',
            'departments' => 'nullable|array',
            'departments.*' => 'nullable|exists:departments,id',
        ];
    }
}
