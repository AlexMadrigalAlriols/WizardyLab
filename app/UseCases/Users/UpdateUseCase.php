<?php

namespace App\UseCases\Users;


use App\Models\User;
use App\UseCases\Core\UseCase;
use Carbon\Carbon;

class UpdateUseCase extends UseCase
{
    public function __construct(
        protected User $User,
        protected string $name,
        protected Carbon $birthday_date,
        protected string $email,
        protected ?string $reporting_user_id,
        protected string $gender,
        protected int $department_id,
        protected int $country_id,
        protected int $role_id,
        protected int $attendance_template_id
    ) {
    }

    public function action(): User
    {
        $this->User->update([
            'name' => $this->name,
            'birthday_date' => $this->birthday_date,
            'email' => $this->email,
            'reporting_user_id' => $this->reporting_user_id,
            'gender' => $this->gender,
            'department_id' => $this->department_id,
            'country_id' => $this->country_id,
            'role_id' => $this->role_id,
            'attendance_template_id' => $this->attendance_template_id
        ]);

        return $this->User;
    }
}
