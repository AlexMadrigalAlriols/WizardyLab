@extends('layouts.dashboard', ['section' => 'Roles'])

@section('content')

<div class="row">
    <div class="col-md-8 col-sm-12">
        <div class="mt-1">
            <span class="h2 d-inline-block mt-1">
                <b>Edit role</b>
            </span>
        </div>
        <form action="{{route('dashboard.roles.update', $role->id)}}" method="POST" class="mt-2 pb-3">
            @method('PUT')

            @include('partials.roles.form')
            <div class="row mt-4">
                <div class="col-md-12 text-end">
                    <a class="btn btn-outline-primary" href="{{route('dashboard.roles.index')}}"><span class="px-2">{{__('global.cancel')}}</span></a>
                    <button class="btn btn-primary ms-2" id="submitBtn"><span class="px-5">{{__("global.edit")}} {{__("crud.roles.title_singular")}}</span></button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
    @parent
    <script>
        var obligatoryFields = ['name'];
        var limitedCharFields = ['name'];
        var allChecked = true;

        $('input, select, textarea').each(function() {
            $(this).on('keyup', function() {
                checkObligatoryFields(obligatoryFields);
            });
        });

        countChars(limitedCharFields);
        checkObligatoryFields(obligatoryFields);


        $('#check_all').change(function() {
                if (this.checked) {
                    // Select all options in all select elements
                    $('.select2').each(function() {
                        var allValues = [];
                        $(this).find('option').each(function() {
                            allValues.push($(this).val());
                        });
                        $(this).val(allValues).trigger('change');
                    });
                } else {
                    // Deselect all options in all select elements
                    $('.select2').val(null).trigger('change');
                }
            });

        // $('.permissions-check').each(function() {
        //     console.log($(this).prop('checked'));
        //     if(!$(this).prop('checked')) {
        //         allChecked = false;
        //     }
        // });

        // $('#check_all').prop('checked', allChecked);
    </script>
@endsection
