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
            'email' => 'required|string|max:30|unique:users,email,'.$this->user()->id,
            'gender' => 'required|string|in:' . implode(',', array_keys(User::GENDERS)),
            'birthday_date' => 'required|date',
            'code' => 'required|unique:users,code,'.$this->user()->id,
            'department_id' => 'required|int|exists:departments,id',
            'country_id' => 'required|int|exists:countries,id',
            'role_id' => 'required|int|exists:roles,id',
        ];
    }

}
