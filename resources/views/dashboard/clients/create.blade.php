@extends('layouts.dashboard', ['section' => 'Clients'])

@section('content')

<div class="row">
    <div class="col-md-8 col-sm-12">
        <div class="mt-1">
            <span class="h2 d-inline-block mt-1">
                <b>{{__('global.create')}} {{__('crud.clients.title_singular')}}</b>
            </span>
        </div>
        <form action="{{route('dashboard.clients.store')}}" method="POST" class="mt-2 pb-3">
            @include('partials.clients.form')
            <div class="row mt-4">
                <div class="col-md-12 text-end">
                    <a class="btn btn-outline-primary" href="{{route('dashboard.clients.index')}}"><span class="px-2">{{ __('global.cancel') }}</span></a>
                    <button class="btn btn-primary ms-2" disabled id="submitBtn"><span class="px-5">{{__('global.create')}} {{__('crud.clients.title_singular')}}</span></button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
    @parent
    <script>
        var obligatoryFields = ['name', 'email', 'currency_id', 'active'];
        var limitedCharFields = ['name', 'address', 'city', 'state', 'zip'];

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
