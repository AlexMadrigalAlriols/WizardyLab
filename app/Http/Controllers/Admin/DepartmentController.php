<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Departments\StoreRequest;
use App\Http\Requests\Departments\UpdateRequest;
use App\Models\Department;
use App\UseCases\Departments\StoreUseCase;
use App\UseCases\Departments\UpdateUseCase;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Department::query();

        [$query, $pagination] = PaginationHelper::getQueryPaginated($query, $request, Department::class);

        $departments = $query->get();
        $total = $query->count();

        return view('dashboard.departments.index', compact('departments', 'total', 'pagination'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {   $department = new Department();
        return view('dashboard.departments.create', compact('department'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request) {
        $client = (new StoreUseCase(
            $request->input('name'),
        ))->action();

        toast('Department created', 'success');
        return redirect()->route('dashboard.departments.index');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        return view('dashboard.departments.edit', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Department $department)
    {
        $company = (new UpdateUseCase(
            $department,
            $request->input('name'),
        ))->action();

        toast('Department updated', 'success');
        return redirect()->route('dashboard.departments.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        $department->delete();

        toast('Department updated', 'success');
        return redirect()->route('dashboard.departments.index');
    }
}