@extends('layouts.dashboard', ['section' => 'Invoices'])

@section('content')

<div class="row">
    <div class="col-md-10 col-sm-12">
        <div class="mt-1">
            <span class="h2 d-inline-block mt-1">
                <b>{{__('global.create')}} {{__('crud.invoices.title_singular')}}</b>
            </span>
        </div>
        <form action="{{route('dashboard.invoices.store')}}" method="POST" class="mt-2 pb-3">
            @include('partials.invoices.form')
            <div class="row mt-4">
                <div class="col-md-12 text-end">
                    <a class="btn btn-outline-primary" href="{{route('dashboard.invoices.index')}}"><span class="px-2">{{ __('global.cancel') }}</span></a>
                    <button class="btn btn-primary ms-2" disabled id="submitBtn"><span class="px-5">{{__('global.create')}} {{__('crud.invoices.title_singular')}}</span></button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
    var obligatoryFields = ['type', 'status_id', 'client_id', 'issue_date'];

    $('#type').change(function() {
        if ($(this).val() == 'tasks') {
            $('#manual').addClass('d-none');
            $('#tasks').removeClass('d-none');
            $('#project').addClass('d-none');
            $('#manualValues').addClass('d-none');
            obligatoryFields = ['type', 'status_id', 'client_id', 'issue_date', 'tasks'];
        } else if ($(this).val() == 'projects') {
            $('#manual').addClass('d-none');
            $('#tasks').addClass('d-none');
            $('#project').removeClass('d-none');
            $('#manualValues').addClass('d-none');
            obligatoryFields = ['type', 'status_id', 'issue_date', 'project_id'];
        } else {
            $('#manual').removeClass('d-none');
            $('#tasks').addClass('d-none');
            $('#project').addClass('d-none');
            $('#manualValues').removeClass('d-none');
            obligatoryFields = ['type', 'status_id', 'issue_date', 'client_id'];
        }
    })

    $('input, select, textarea').each(function() {
        $(this).on('keyup', function() {
            checkObligatoryFields(obligatoryFields);
        });
        $(this).on('change', function() {
            checkObligatoryFields(obligatoryFields);
        });
    });
</script>
@endsection
