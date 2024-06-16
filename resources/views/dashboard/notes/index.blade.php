@extends('layouts.dashboard', ['section' => 'Notes'])

@section('content')
    <div class="mt-2">
        <span class="h2 d-inline-block mt-1">
            <b>{{__('crud.notes.title')}}</b><span class="text-muted">({{count($notes)}})</span>
        </span>
        <a class="btn btn-primary d-inline-block ms-3 align-top" href="{{route('dashboard.notes.create')}}">
            <span class="px-4"><i class="bx bx-plus mt-1"></i>{{__('global.create')}} {{ __('crud.notes.title_singular')}}</span>
        </a>
    </div>

    <div class="row mt-3">
        @foreach ($notes as $note)
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        {!! $note->content !!}

                        <div class="row">
                            <div class="col-md-6">
                                @if($note->date)
                                    <div class="text-muted">
                                        <small><i class="bx bx-calendar"></i> {{$note->date}}</small>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6 text-end">
                                <a href="{{route('dashboard.notes.edit', $note->id)}}" class="btn btn-primary btn-sm me-2">
                                    <i class="bx bx-edit"></i>
                                </a>

                                <form action="{{route('dashboard.notes.destroy', $note->id)}}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        @if(!count($notes))
            <div class="col-md-12 mt-5 text-center">
                @include('images.no_notes_found')
                <p class="text-muted mt-3">No notes found here. Write something!</p>
            </div>
        @endif
    </div>
@endsection
