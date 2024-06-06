@extends('layouts.dashboard', ['section' => 'Projects'])

@section('content')

<div class="row">
    <div class="col-md-8 col-sm-12">
        <div class="mt-1">
            <span class="h2 d-inline-block mt-1">
                <b>Create a project</b>
            </span>
        </div>
        <form action="{{route('dashboard.projects.store')}}" method="POST" class="mt-2 pb-3">
            @include('partials.projects.form')
            <div class="row mt-4">
                <div class="col-md-12 text-end">
                    <a class="btn btn-outline-primary" href="{{route('dashboard.projects.index')}}"><span class="px-2">Cancel</span></a>
                    <button class="btn btn-primary ms-2" disabled id="submitBtn"><span class="px-5">Create Project</span></button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
    @parent
    <script>
        var obligatoryFields = ['name', 'code', 'status'];
        var limitedCharFields = ['name', 'description', 'code'];

        $('input, select, textarea').each(function() {
            $(this).on('keyup', function() {
                console.log('keyup');
                checkObligatoryFields(obligatoryFields);
            });
            $(this).on('change', function() {
                checkObligatoryFields(obligatoryFields);
            });
        });

        countChars(limitedCharFields);
    </script>
@endsection
