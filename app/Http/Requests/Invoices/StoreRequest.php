<?php

namespace App\Http\Requests\Invoices;

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
            'type' => 'required|string|in:tasks,projects,manual',
            'project_id' => 'nullable|exists:projects,id',
            'tasks' => 'nullable|array',
            'tasks.*' => 'nullable|exists:tasks,id',
            'amount' => 'nullable|numeric',
            'status_id' => 'required|exists:statuses,id',
            'items' => 'nullable|array'
        ];
    }
}
