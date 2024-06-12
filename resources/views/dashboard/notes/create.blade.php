@extends('layouts.dashboard', ['section' => 'Notes'])

@section('content')

<div class="row">
    <div class="col-md-8 col-sm-12">
        <div class="mt-1">
            <span class="h2 d-inline-block mt-1">
                <b>{{__('global.create')}} {{__('crud.notes.title_singular')}}</b>
            </span>
        </div>
        <form action="{{route('dashboard.notes.store')}}" method="POST" class="mt-2 pb-3">
            @include('partials.notes.form')
            <div class="row mt-4">
                <div class="col-md-12 text-end">
                    <a class="btn btn-outline-primary" href="{{route('dashboard.notes.index')}}"><span class="px-2">{{__('global.cancel')}}</span></a>
                    <button class="btn btn-primary ms-2"><span class="px-5">{{__('global.create')}} {{__('crud.notes.title_singular')}}</span></button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
