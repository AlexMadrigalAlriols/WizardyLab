<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($data, [
            'first_name'     => ['required', 'string', 'max:255'],
            'last_name'     => ['required', 'string', 'max:255'],
            'phone'     => ['required', 'numeric', 'unique:users,phone'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'email.required' => __('global.auth.invalid_field', ['field' => __('cruds.users.fields.email_long')]),
            'email.email' => __('global.auth.invalid_field', ['field' => __('cruds.users.fields.email_long')]),
            'email.max' => __('global.auth.invalid_field', ['field' => __('cruds.users.fields.email_long')]),
            'email.unique' => __('global.auth.field_on_use', ['field' => __('cruds.users.fields.email_long')]),
            'phone.required' => __('global.auth.invalid_field', ['field' => __('cruds.users.fields.phone')]),
            'phone.numeric' => __('global.auth.invalid_field', ['field' => __('cruds.users.fields.phone')]),
            'phone.unique' => __('global.auth.field_on_use', ['field' => __('cruds.users.fields.phone')]),
            'password.required' => __('global.auth.password_error'),
            'password.min' => __('global.auth.password_error'),
            'password.confirmed' => __('global.auth.password_not_match')
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\Models\User
     */
    protected function create(array $data): User
    {
        return User::create([
            'first_name'    => $data['first_name'],
            'last_name'    => $data['last_name'],
            'phone'    => $data['phone'],
            'email'    => $data['email'],
            'code'  => User::generateCode(),
            'password' => Hash::make($data['password']),
        ]);
    }
}
