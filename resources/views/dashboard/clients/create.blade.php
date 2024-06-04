@extends('layouts.dashboard', ['section' => 'Clients'])

@section('content')

<div class="row">
    <div class="col-md-8 col-sm-12">
        <div class="mt-1">
            <span class="h2 d-inline-block mt-1">
                <b>Create a client</b>
            </span>
        </div>
        <form action="{{route('dashboard.clients.store')}}" method="POST" class="mt-2 pb-3">
            @include('partials.clients.form')
            <div class="row mt-4">
                <div class="col-md-8 text-end">
                    <a class="btn btn-outline-primary" href="{{route('dashboard.clients.index')}}"><span class="px-2">Cancel</span></a>
                    <button class="btn btn-primary ms-2"><span class="px-5">Create Client</span></button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
