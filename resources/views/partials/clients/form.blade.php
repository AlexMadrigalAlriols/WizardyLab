@csrf
<div class="row">
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="{{ old('name') ?? $client->name }}">
            <label for="title">Name <span class="text-danger">*</span></label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <select class="form-select select2" id="company_id" name="company_id" aria-label="Company">
                @foreach ($companies as $company)
                    <option value="{{ $company->id }}" {{$client->company_id == $company->id || old('company_id') == $company->id ? 'selected' : ''}}>{{ $company->name }}</option>
                @endforeach
            </select>
            <label for="floatingSelect">Company</label>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-floating mt-3">
            <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{ old('email') ?? $client->email }}">
            <label for="email">Email <span class="text-danger">*</span></label>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-floating mt-3">
            <input type="text" class="form-control" id="phone" name="phone" placeholder="Mobile Phone" value="{{ old('phone') ?? $client->phone }}">
            <label for="phone">Phone</label>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-floating mt-3">
            <input type="text" class="form-control" id="vat_number" name="vat_number" placeholder="Vat Number" value="{{ old('vat_number') ?? $client->vat_number }}">
            <label for="vat_number">VAT Number</label>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <select class="form-select select2" id="currency_id" name="currency_id" aria-label="Currency">
                @foreach ($currencies as $currency)
                    <option value="{{ $currency->id }}" {{$client->currency_id == $currency->id || old('currency_id') == $currency->id ? 'selected' : ''}}>{{ $currency->name }} ({{$currency->iso_code}})</option>
                @endforeach
            </select>
            <label for="floatingSelect">Currency <span class="text-danger">*</span></label>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-floating mt-3">
            <select class="form-select select2" id="language_id" name="language_id" aria-label="Language">
                @foreach ($languages as $language)
                    <option value="{{ $language->id }}" {{$client->language_id == $language->id || old('language_id') == $language->id ? 'selected' : ''}}>{{ $language->name }}</option>
                @endforeach
            </select>
            <label for="floatingSelect">Language</label>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <select class="form-select" id="active" name="active" aria-label="Active">
                <option value="0" {{$client->active == 0 || old('active') == 0 ? 'selected' : ''}}>{{__('global.no')}}</option>
                <option value="1" {{$client->active == 1 || old('active') == 1 ? 'selected' : ''}}>{{__('global.yes')}}</option>
            </select>
            <label for="floatingSelect">{{__('crud.companies.fields.active')}} <span class="text-danger">*</span></label>
        </div>
    </div>
</div>
<h6 class="mt-4">Client Address</h6>
<div class="row">
    <div class="col-md-4">
        <div class="form-floating mt-3">
            <input type="text" class="form-control" id="address" name="address" placeholder="Address" value="{{ old('address') ?? $client->address }}">
            <label for="address">Address</label>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-floating mt-3">
            <input type="text" class="form-control" id="city" name="city" placeholder="City" value="{{ old('city') ?? $client->city }}">
            <label for="city">City</label>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-floating mt-3">
            <input type="text" class="form-control" id="zip" name="zip" placeholder="Zip" value="{{ old('zip') ?? $client->zip }}">
            <label for="zip">Zip</label>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <input type="text" class="form-control" id="state" name="state" placeholder="State" value="{{ old('state') ?? $client->state }}">
            <label for="state">State</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <select class="form-select select2" id="country_id" name="country_id" aria-label="Country">
                @foreach ($countries as $country)
                    <option value="{{ $country->id }}" {{$client->country_id == $country->id || old('country_id') == $country->id ? 'selected' : ''}}>{{ $country->name }}</option>
                @endforeach
            </select>
            <label for="floatingSelect">Country</label>
        </div>
    </div>
</div>
