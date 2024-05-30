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
                <marketplace-rule
                    :return-url="'{{ route('dashboard.automation.index', $project->id) }}'"
                    :form-url="'{{ $copy ? route('dashboard.automation.store', $project->id) : route('dashboard.automation.update', [$project->id, $automation->id]) }}'"
                    :triggers="{{ $triggers }}"
                    :trigger-types="{{ $triggerTypes }}"
                    :actions="{{ $actions }}"
                    :action-types="{{ $actionTypes }}"
                    :form-data="{{ $formData }}">
                </marketplace-rule>
            </div>
        </div>
    </div>
@endsection
