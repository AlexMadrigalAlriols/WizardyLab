@extends('layouts.dashboard', ['section' => 'DeliveryNotes'])

@section('content')

<div class="row">
    <div class="col-md-10 col-sm-12">
        <div class="mt-1">
            <span class="h2 d-inline-block mt-1">
                <b>{{__('global.create')}} {{__('crud.deliveryNotes.title_singular')}}</b>
            </span>
        </div>
        <form action="{{route('dashboard.deliveryNotes.store')}}" method="POST" class="mt-2 pb-3">
            @include('partials.deliveryNotes.form')
            <div class="row mt-4">
                <div class="col-md-12 text-end">
                    <a class="btn btn-outline-primary" href="{{route('dashboard.deliveryNotes.index')}}"><span class="px-2">{{__('global.cancel')}}</span></a>
                    <button class="btn btn-primary ms-2" disabled id="submitBtn"><span class="px-5">{{__('global.create')}} {{__('crud.deliveryNotes.title_singular')}}</span></button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
    var obligatoryFields = ['type', 'client_id', 'issue_date'];
    var limitedCharFields = ['notes'];

    $('#generate_invoice').on('change', function() {
        if ($(this).val() == 1) {
            $('#substract_stock_container').addClass('d-none');
            $('#generate_invoice_container').addClass('col-md-6').removeClass('col-md-3');
        } else {
            $('#substract_stock_container').removeClass('d-none');
            $('#generate_invoice_container').addClass('col-md-3').removeClass('col-md-6');
        }
    });

    $('input, select, textarea').each(function() {
        $(this).on('keyup', function() {
            checkObligatoryFields(obligatoryFields);
        });
        $(this).on('change', function() {
            checkObligatoryFields(obligatoryFields);
        });
    });

    countChars(limitedCharFields);
    checkObligatoryFields(obligatoryFields);
</script>
@endsection
