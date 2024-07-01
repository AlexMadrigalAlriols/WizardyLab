@extends('layouts.dashboard', ['section' => 'Tasks'])

@section('content')
<div class="row mt-2 h-100 ">
    <div class="col-md-8 col-sm-12">
        <div class="mt-2">
            <span class="h2 d-inline-block mt-1">
                <b>{{__('global.save')}} {{ $task->title }}</b>
            </span>
        </div>
        <form action="{{route('dashboard.tasks.update', $task->id)}}" method="POST" class="mb-4 pb-2">
            @method('PUT')

            @include('partials.tasks.form')
            <div class="row mt-4">
                <div class="col-md-12 text-end">
                    <a class="btn btn-outline-primary" href="{{request()->has('board') ? route('dashboard.projects.board', request()->input('board')) : route('dashboard.tasks.index')}}"><span class="px-2">{{__('global.cancel')}}</span></a>
                    <button class="btn btn-primary ms-2" id="submitBtn"><span class="px-5">{{__('global.save')}} {{__('crud.tasks.title_singular')}}</span></button>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-4 border-start">
        <div class="pt-2 border-bottom">
            <span class="h2 d-inline-block mt-1">
                <b>Files</b>
            </span>
        </div>

        @foreach ($task->files as $file)
            <div class="row border-top border-bottom p-3">
                <div class="col-md-10 col-9">
                    <p class="mt-1 mb-1">
                        <i class="{{$file->icon}} text-muted"></i>
                        {{$file->title}}
                    </p>
                    <p class="text-muted" style="font-size: 14px">{{$file->real_size}} | <a href="{{$file->user_id}}">{{$file->user->name}}</a> | {{$file->created_at->format('jS M, h:i A')}}</p>

                    @if($file->is_image)
                        <img src="{{ asset('storage/' . $file->path) }}" class="img-fluid" alt="{{$file->title}}" style="max-width: 250px">
                    @endif
                </div>
                <div class="col-md-2 col-3">
                    <div class="col-md-2 text-end">
                        <div class="dropdown">
                            <button class="btn btn-options" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class='bx bx-dots-horizontal-rounded'></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li><a class="dropdown-item" href="{{$file->download_link}}"><i class='bx bxs-download' ></i> Download</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{route('dashboard.task.delete_file', $file->id)}}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="dropdown-item text-danger"><i class='bx bx-trash' ></i> Remove</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        @if($task->files->isEmpty())
            <div class="text-center p-3">
                <p class="text-muted">No files uploaded yet!</p>
            </div>
        @endif
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

        countChars(['title', 'description', 'code']);
    </script>
@endsection
