<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\DataTables\RolesDataTable;
use App\Http\Requests\MassDestroyRequest;
use App\Http\Requests\Roles\StoreRequest;
use App\Http\Requests\Roles\UpdateRequest;
use App\Models\Role;
use App\UseCases\Roles\StoreUseCase;
use App\UseCases\Roles\UpdateUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            $dataTable = new RolesDataTable('roles');
            return $dataTable->make();
        }

        $query = Role::query();
        $total = $query->count();

        return view('dashboard.roles.index', compact('total'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {   $role = new Role();
        $permissions = Permission::all();
        return view('dashboard.roles.create', compact('role', 'permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request) {
        $permissions = $this->getPermissionsFromInput($request->input('permissions'));

        $role = (new StoreUseCase(
            $request->input('name'),
            $permissions
        ))->action();

        toast('Role created', 'success');
        return redirect()->route('dashboard.roles.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all();
        return view('dashboard.roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Role $role)
    {
        $permissions = $this->getPermissionsFromInput($request->input('permissions'));

        $role = (new UpdateUseCase(
            $role,
            $request->input('name'),
            $permissions
        ))->action();

        toast('Role updated', 'success');
        return redirect()->route('dashboard.roles.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();

        toast('Role updated', 'success');
        return redirect()->route('dashboard.roles.index');
    }

    public function massDestroy(MassDestroyRequest $request)
    {
        $ids = $request->input('ids');
        Role::whereIn('id', $ids)->delete();

        toast('Roles deleted', 'success');
        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function getPermissionsFromInput(array $input): array
    {
        $permissions = [];

        foreach ($input as $name => $value) {
            $permissions[] = $name;
        }

        return $permissions;
    }
}
