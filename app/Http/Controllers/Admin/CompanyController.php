<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Companies\StoreRequest;
use App\Http\Requests\Companies\UpdateRequest;
use App\Models\Company;
use App\UseCases\Companies\StoreUseCase;
use App\UseCases\Companies\UpdateUseCase;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = Company::query();

        [$query, $pagination] = PaginationHelper::getQueryPaginated($query, $request, Company::class);

        $companies = $query->get();
        $total = $query->count();

        return view('dashboard.companies.index', compact('companies', 'total', 'pagination'));
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
}
