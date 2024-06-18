@extends('layouts.dashboard', ['section' => 'Tasks'])

@section('content')
    <div class="row">
        <div class="col-md-8 col-sm-12">
            <div class="mt-1">
                <span class="h2 d-inline-block mt-1">
                    <b>Create a task</b>
                </span>
            </div>
            <form action="{{route('dashboard.tasks.store')}}" method="POST" class="mt-2 pb-2">
                @include('partials.tasks.form')
                <div class="row mt-4">
                    <div class="col-md-12 text-end">
                        <a class="btn btn-outline-primary" href="{{request()->has('board') ? route('dashboard.projects.board', request()->input('board')) : route('dashboard.tasks.index')}}"><span class="px-2">Cancel</span></a>
                        <button class="btn btn-primary ms-2" disabled id="submitBtn"><span class="px-5">Create Task</span></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script>
        var obligatoryFields = ['title', 'priority', 'status'];
        var limitedCharFields = ['title', 'description'];
        Dropzone.autoDiscover = false;

        $('input, select, textarea').each(function() {
            $(this).on('keyup', function() {
                checkObligatoryFields(obligatoryFields);
            });
            $(this).on('change', function() {
                checkObligatoryFields(obligatoryFields);
            });
        });

        $(document).ready(function() {
            generateDropZone(
                "#taskDropZone",
                "{{ route('dashboard.task.upload_file') }}",
                "{{ csrf_token() }}",
                true
            );
        });

        countChars(limitedCharFields);
checkObligatoryFields(obligatoryFields);
    </script>
@endsection
