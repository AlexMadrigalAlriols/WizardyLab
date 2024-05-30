@extends('layouts.dashboard', ['section' => 'Projects'])

@section('styles')
    <style>
        .height-100 {
            height: calc(100vh - 10rem) !important;
        }
    </style>
@endsection
@section('content_with_padding')
    @include('partials.board.menu')
@endsection

@section('content')
<div class="row">
    <div class="card">
        <div class="card-body">
            <marketplace-rule :return-url="'{{route('dashboard.automation.index', $project->id)}}'" :form-url="'{{route('dashboard.automation.store', $project->id)}}'" :triggers="{{$triggers}}" :trigger-types="{{$triggerTypes}}" :actions="{{$actions}}" :action-types="{{$actionTypes}}"></marketplace-rule>
        </div>
    </div>
</div>
@endsection
