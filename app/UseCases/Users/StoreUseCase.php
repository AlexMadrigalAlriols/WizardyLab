<?php

namespace App\UseCases\Users;

use App\Models\Portal;
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
        protected string $gender,
        protected int $department_id,
        protected int $country_id,
        protected int $role_id,
        protected string $password,
        protected ?Portal $portal = null
    ) {
    }

    public function action(): User
    {
        $code = $this->generateCode();
        $data = [
            'name' => $this->name,
            'birthday_date' => $this->birthday_date,
            'email' => $this->email,
            'reporting_user_id' => $this->reporting_user_id,
            'gender' => $this->gender,
            'department_id' => $this->department_id,
            'country_id' => $this->country_id,
            'role_id' => $this->role_id,
            'code' => $code,
            'password' => Hash::make($this->password),
        ];

        if($this->portal) {
            $data['portal_id'] = $this->portal->id;
        }

        $user = User::create($data);

        return $user;
    }

    public static function generateCode(): string
    {
        $query = User::query();

        $query->orderBy('id', 'desc');
        $user = $query->first();

        if($user) {
            $code = $user->code;
            $code = substr($code, strpos($code, '-') + 1);
            $code = (int) $code + 1;
            $code = str_pad($code, 5, '0', STR_PAD_LEFT);

            return 'U-' . $user->portal_id . $code;
        }

        return 'U-00001';
    }
}