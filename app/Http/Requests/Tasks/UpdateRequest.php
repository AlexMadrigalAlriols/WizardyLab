<?php

namespace App\Http\Requests\Tasks;

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
            'title' => 'required|string|max:50',
            'description' => 'nullable|string|max:1000',
            'priority' => 'required|string|in:' . implode(',', array_keys(Task::PRIORITIES)),
            'status' => 'required|exists:statuses,id',
            'limit_hours' => 'nullable|numeric',
            'due_date' => 'nullable|date',
            'start_date' => 'nullable|date',
            'tags' => 'array',
            'tags.*' => 'exists:labels,id',
            'assigned_users' => 'array',
            'assigned_users.*' => 'exists:users,id',
            'departments' => 'array',
            'departments.*' => 'exists:departments,id',
            'project' => 'nullable|exists:projects,id',
            'parent_task' => 'nullable|exists:tasks,id',
            'board' => 'nullable|exists:projects,id',
            'images' => 'nullable|array',
        ];
    }
}
