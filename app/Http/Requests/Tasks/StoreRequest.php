<?php

namespace App\Http\Requests\Tasks;

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
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'project' => $this->input('project') === '' ? null : $this->input('project'),
        ]);
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
            'tags' => 'nullable|array',
            'tags.*' => 'nullable|exists:labels,id',
            'assigned_users' => 'nullable|array',
            'assigned_users.*' => 'nullable|exists:users,id',
            'departments' => 'nullable|array',
            'departments.*' => 'nullable|exists:departments,id',
            'project' => 'nullable|exists:projects,id',
            'parent_task' => 'nullable|exists:tasks,id',
            'images' => 'nullable|array',
        ];
    }
}
