<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\DataTables\CompaniesDataTable;
use App\Http\Requests\Companies\StoreRequest;
use App\Http\Requests\Companies\UpdateRequest;
use App\Http\Requests\MassDestroyRequest;
use App\Models\Company;
use App\UseCases\Companies\StoreUseCase;
use App\UseCases\Companies\UpdateUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()) {
            $dataTable = new CompaniesDataTable('companies');
            return $dataTable->make();
        }

        $query = Company::query();
        $total = $query->count();

        return view('dashboard.companies.index', compact('total'));
    }

    public function create()
    {
        $company = new Company();

        return view('dashboard.companies.create', compact('company'));
    }

    public function store(StoreRequest $request) {
        $client = (new StoreUseCase(
            $request->input('name'),
            $request->input('active')
        ))->action();

        toast('Company created', 'success');
        return redirect()->route('dashboard.companies.index');
    }

    public function edit(Company $company)
    {
        return view('dashboard.companies.edit', compact('company'));
    }

    public function update(UpdateRequest $request, Company $company)
    {
        $company = (new UpdateUseCase(
            $company,
            $request->input('name'),
            $request->input('active')
        ))->action();

        toast('Company updated', 'success');
        return redirect()->route('dashboard.companies.index');
    }

    public function destroy(Company $company)
    {
        $company->delete();

        toast('Company deleted', 'success');
        return back();
    }

    public function massDestroy(MassDestroyRequest $request)
    {
        $ids = $request->input('ids');
        Company::whereIn('id', $ids)->delete();

        toast('Companies deleted', 'success');
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
