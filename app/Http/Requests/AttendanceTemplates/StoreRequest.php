<?php

namespace App\Http\Requests\AttendanceTemplates;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'background' => 'required|string',
            'color' => 'required|string',
            'start_time' => 'required|array',
            'end_time' => 'required|array',
            'start_break' => 'required|array',
            'end_break' => 'required|array',
        ];
    }
}
