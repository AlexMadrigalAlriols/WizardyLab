@extends('layouts.dashboard', ['section' => 'Labels'])

@section('content')

<div class="row">
    <div class="col-md-8 col-sm-12">
        <div class="mt-1">
            <span class="h2 d-inline-block mt-1">
                <b>{{__('global.edit')}} {{__('crud.labels.title_singular')}}</b>
            </span>
        </div>
        <form action="{{route('dashboard.labels.update', $label->id)}}" method="POST" class="mt-2 pb-3">
            @method('PUT')

            @include('partials.labels.form')
            <div class="row mt-4">
                <div class="col-md-12 text-end">
                    <a class="btn btn-outline-primary" href="{{route('dashboard.labels.index')}}"><span class="px-2">{{__('global.cancel')}}</span></a>
                    <button class="btn btn-primary ms-2" id="submitBtn"><span class="px-5">{{__('global.save')}} {{__('crud.labels.title_singular')}}</span></button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
    @parent
    <script>
        var obligatoryFields = ['name', 'background', 'color'];
        var limitedCharFields = ['name'];

        $('input, select, textarea').each(function() {
            $(this).on('keyup', function() {
                checkObligatoryFields(obligatoryFields);
            });
        });

        countChars(limitedCharFields);
    </script>
@endsection
