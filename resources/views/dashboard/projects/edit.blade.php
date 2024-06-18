@extends('layouts.dashboard', ['section' => 'Projects'])

@section('content')

<div class="row">
    <div class="col-md-8 col-sm-12">
        <div class="mt-1">
            <span class="h2 d-inline-block mt-1">
                <b>Edit {{ $project->name }}</b>
            </span>
        </div>
        <form action="{{route('dashboard.projects.update', $project->id)}}" method="POST" class="mt-2 pb-3">
            @method('PUT')

            @include('partials.projects.form')
            <div class="row mt-4">
                <div class="col-md-12 text-end">
                    <a class="btn btn-outline-primary" href="{{route('dashboard.projects.index')}}"><span class="px-2">Cancel</span></a>
                    <button class="btn btn-primary ms-2" id="submitBtn"><span class="px-5">Save Project</span></button>
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
