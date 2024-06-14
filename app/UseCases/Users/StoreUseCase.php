<?php

namespace App\UseCases\Users;

use App\Models\User;
use App\UseCases\Core\UseCase;
use Carbon\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;

class StoreUseCase extends UseCase
{
    public function __construct(
        protected string $name,
        protected Carbon $birthday_date,
        protected string $email,
        protected ?string $reporting_user_id,
        protected string $code,
        protected string $gender,
        protected int $department_id,
        protected int $country_id,
        protected int $role_id,
        protected string $password,
    ) {
    }

    public function action(): User
    {
        $UserInventories = User::create([
            'name' => $this->name,
            'birthday_date' => $this->birthday_date,
            'email' => $this->email,
            'reporting_user_id' => $this->reporting_user_id,
            'code' => $this->code,
            'gender' => $this->gender,
            'department_id' => $this->department_id,
            'country_id' => $this->country_id,
            'role_id' => $this->role_id,
            'password' => Hash::make($this->password),
        ]);

        return $UserInventories;
    }
}
