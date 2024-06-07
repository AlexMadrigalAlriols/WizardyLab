@extends('layouts.dashboard', ['section' => 'Leaves'])

@section('content')

<div class="row">
    <div class="col-md-8 col-sm-12">
        <div class="mt-1">
            <span class="h2 d-inline-block mt-1">
                <b>{{__('global.edit')}} {{__('crud.leaves.title_singular')}}</b>
            </span>
        </div>
        <form action="{{route('dashboard.leaves.update', $leave->id)}}" method="POST" class="mt-2 pb-3">
            @method('PUT')

            @include('partials.leaves.form')
            <div class="row mt-4">
                <div class="col-md-12 text-end">
                    <a class="btn btn-outline-primary" href="{{route('dashboard.leaves.index')}}"><span class="px-2">{{__('global.cancel')}}</span></a>
                    <button class="btn btn-primary" id="submitBtn"><span class="px-5">{{__('global.save')}} {{__('crud.leaves.title_singular')}}</span></button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
    @parent
    <script>
        var obligatoryFields = ['type', 'duration', 'date', 'user_id'];
        var limitedCharFields = ['reason'];

        $('input, select, textarea').each(function() {
            $(this).on('keyup', function() {
                checkObligatoryFields(obligatoryFields);
            });
            $(this).on('change', function() {
                checkObligatoryFields(obligatoryFields);
            });
        });

        $('#duration').change(function() {
            if ($(this).val() == 'single') {
                $('#date').removeClass('multiple')
            } else {
                $('#date').addClass('multiple');
            }
        });

        countChars(limitedCharFields);
    </script>
@endsection
