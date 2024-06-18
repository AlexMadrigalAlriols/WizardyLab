<?php

namespace App\Http\Requests\Users;

use App\Models\User;
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
            'email' => 'required|string|max:60|unique:users,email,'.$this->route('user')->id,
            'gender' => 'required|string|in:' . implode(',', array_keys(User::GENDERS)),
            'reporting_user_id' => 'nullable|int|exists:users,id|not_in:'.$this->route('user')->id.",".$this->route('user')->reportinguser,
            'birthday_date' => 'required|date',
            'department_id' => 'nullable|int|exists:departments,id',
            'country_id' => 'required|int|exists:countries,id',
            'role_id' => 'required|int|exists:roles,id',
            'attendance_template_id' => 'nullable|int|exists:attendance_templates,id',
        ];
    }

}
