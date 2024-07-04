<?php

namespace App\UseCases\Roles;

use App\Models\Role;
use App\UseCases\Core\UseCase;

class UpdateUseCase extends UseCase
{
    public function __construct(
        protected Role $role,
        protected string $name,
        protected array $permissions = [],
    ) {
    }

    public function action(): Role
    {
        $this->role->update([
            'name' => $this->name,
        ]);

        $this->role->permissions()->sync($this->permissions);

        return $this->role;
    }
}
