<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = Permission::all();
        if($role = Role::where('name', 'CEO')->first()) {
            foreach ($permissions as $permission) {
                $role->permissions()->attach($permission->id);
            }
            return;
        }

        $role = Role::create(['name' => 'CEO', 'guard_name' => 'web', 'portal_id' => 1]);
        foreach ($permissions as $permission) {
            $role->permissions()->attach($permission->id);
        }
    }
}
