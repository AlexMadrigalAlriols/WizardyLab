@csrf
<div class="row">
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <select class="form-select @if($errors->has('type')) is-invalid @endif" id="type" name="type" aria-label="Type">
                @foreach ($types as $type)
                    <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                @endforeach
            </select>
            <label for="floatingSelect">Type <span class="text-danger">*</span></label>
        </div>
        <span class="text-muted">Con "Valued" se mostraran los precios en el albaran.</span>

        @if ($errors->has('type'))
            <span class="text-danger">{{ $errors->first('type') }}</span>
        @endif
    </div>
    <div class="col-md-3" id="generate_invoice_container">
        <div class="form-floating mt-3">
            <select class="form-select @if($errors->has('generate_invoice')) is-invalid @endif" id="generate_invoice" name="generate_invoice" aria-label="Generate Invoice">
                <option value="0">{{trans('global.no')}}</option>
                <option value="1">{{trans('global.yes')}}</option>
            </select>
            <label for="floatingSelect">Generate Invoice <span class="text-danger">*</span></label>
        </div>

        @if ($errors->has('generate_invoice'))
            <span class="text-danger">{{ $errors->first('generate_invoice') }}</span>
        @endif
    </div>
    <div class="col-md-3" id="substract_stock_container">
        <div class="form-floating mt-3">
            <select class="form-select @if($errors->has('substract_stock')) is-invalid @endif" id="substract_stock" name="substract_stock" aria-label="Substract Stock">
                <option value="0">{{trans('global.no')}}</option>
                <option value="1" selected>{{trans('global.yes')}}</option>
            </select>
            <label for="floatingSelect">Substract Stock <span class="text-danger">*</span></label>
        </div>

        @if ($errors->has('substract_stock'))
            <span class="text-danger">{{ $errors->first('substract_stock') }}</span>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <select class="form-select @if($errors->has('client_id')) is-invalid @endif" id="client_id" name="client_id" aria-label="Client">
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}" {{$deliveryNote->client_id == $client->id || old('client_id') == $client->id ? 'selected' : ''}}>{{ $client->name }}</option>
                @endforeach
            </select>
            <label for="floatingSelect">{{__('crud.invoices.fields.client')}} <span class="text-danger">*</span></label>
        </div>

        @if ($errors->has('client_id'))
            <span class="text-danger">{{ $errors->first('client_id') }}</span>
        @endif
    </div>

    <div class="col-md-6">
        <div class="form-floating mt-3">
            <input type="date" class="form-control flatpicker @if($errors->has('issue_date')) is-invalid @endif" id="issue_date" name="issue_date" placeholder="Address" value="{{ old('issue_date') ?? now()->format('Y-m-d') }}">
            <label for="issue_date">{{__('crud.invoices.fields.issue_date')}} <span class="text-danger">*</span></label>
        </div>

        @if ($errors->has('issue_date'))
            <span class="text-danger">{{ $errors->first('issue_date') }}</span>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-floating mt-3">
            <textarea name="notes" id="notes" class="form-control" maxlength="200"></textarea>
            <label for="issue_date">Observations <span class="text-danger">*</span></label>
        </div>

        <div class="mt-0 text-end">
            <span class="text-muted"><span id="notesCountChar">0</span>/200</span>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-12">
        <expenses-form :inventory-items="{{json_encode($inventoryArray)}}" :assignments="{{json_encode([])}}"></expenses-form>

        @if ($errors->has('items'))
            <span class="text-danger">{{ $errors->first('items') }}</span>
        @endif
    </div>
</div>
