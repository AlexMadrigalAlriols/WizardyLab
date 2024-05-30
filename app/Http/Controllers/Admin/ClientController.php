<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApiResponse;
use App\Helpers\AttendanceHelper;
use App\Helpers\PaginationHelper;
use App\Helpers\TaskAttendanceHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Clients\StoreRequest;
use App\Http\Requests\Clients\UpdateRequest;
use App\Models\Client;
use App\Models\Company;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Language;
use App\Models\Status;
use App\UseCases\Clients\StoreUseCase;
use App\UseCases\Clients\UpdateUseCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::query();

        if($request->has('status') && is_numeric($request->input('status'))) {
            $query->where('status_id', $request->input('status'));
        }

        [$query, $pagination] = PaginationHelper::getQueryPaginated($query, $request, Client::class);

        $clients = $query->get();
        $total = $query->count();
        $statuses = Status::all();

        return view('dashboard.clients.index', compact('clients', 'statuses', 'total', 'pagination'));
    }

    public function show(Client $client)
    {
        return view('dashboard.clients.show', compact('client'));
    }

    public function create()
    {
        $client = new Client();
        [$companies, $languages, $currencies, $countries] = $this->getData();

        return view('dashboard.clients.create', compact(
            'client',
            'companies',
            'languages',
            'currencies',
            'countries'
        ));
    }

    public function store(StoreRequest $request) {
        $client = (new StoreUseCase(
            $request->input('name'),
            $request->input('active'),
            Company::find($request->input('company_id')),
            $request->input('email'),
            Currency::find($request->input('currency_id')),
            $request->input('phone'),
            $request->input('vat_number'),
            Language::find($request->input('language_id')),
            $request->input('address'),
            $request->input('city'),
            $request->input('zip'),
            Country::find($request->input('country_id')),
            $request->input('state')
        ))->action();

        toast('Client created', 'success');
        return redirect()->route('dashboard.clients.index');
    }

    public function edit(Client $client)
    {
        [$companies, $languages, $currencies, $countries] = $this->getData();

        return view('dashboard.clients.edit', compact(
            'client',
            'companies',
            'languages',
            'currencies',
            'countries'
        ));
    }

    public function update(UpdateRequest $request, Client $client)
    {
        $client = (new UpdateUseCase(
            $client,
            $request->input('name'),
            $request->input('active'),
            Company::find($request->input('company_id')),
            $request->input('email'),
            Currency::find($request->input('currency_id')),
            $request->input('phone'),
            $request->input('vat_number'),
            Language::find($request->input('language_id')),
            $request->input('address'),
            $request->input('city'),
            $request->input('zip'),
            Country::find($request->input('country_id')),
            $request->input('state')
        ))->action();

        toast('Client updated', 'success');
        return redirect()->route('dashboard.clients.index');
    }

    public function destroy(Client $client)
    {
        $client->delete();

        toast('Client deleted', 'success');
        return back();
    }

    private function getData()
    {
        // Insert select all on query
        $companies = Company::all()->prepend(new Company());
        $languages = Language::all()->prepend(new Language());
        $currencies = Currency::all();
        $countries = Country::all()->prepend(new Country());

        return [$companies, $languages, $currencies, $countries];
    }
}
