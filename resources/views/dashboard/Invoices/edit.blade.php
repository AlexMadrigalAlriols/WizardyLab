@extends('layouts.dashboard', ['section' => 'Clients'])

@section('content')

<div class="row">
    <div class="col-md-8 col-sm-12">
        <div class="mt-1">
            <span class="h2 d-inline-block mt-1">
                <b>Edit {{ $client->name }}</b>
            </span>
        </div>
        <form action="{{route('dashboard.clients.update', $client->id)}}" method="POST" class="mt-2 pb-3">
            @method('PUT')

            @include('partials.clients.form')
            <div class="row mt-4">
                <div class="col-md-12 text-end">
                    <a class="btn btn-outline-primary" href="{{route('dashboard.clients.index')}}"><span class="px-2">Cancel</span></a>
                    <button class="btn btn-primary ms-2"><span class="px-5">Save Client</span></button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
