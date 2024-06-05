@extends('layouts.dashboard', ['section' => 'GlobalConfigurations'])

@section('content')

<div class="row">
    <div class="col-md-8 col-sm-12">
        <div class="mt-1">
            <span class="h2 d-inline-block mt-1">
                <b>{{__('global.edit')}} {{__('crud.globalConfigurations.title_singular')}}</b>
            </span>
        </div>
        <form action="{{ route('dashboard.global-configurations.store') }}" method="POST" class="mt-2 pb-3">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <span><b>Key</b></span>
                </div>
                <div class="col-md-6">
                    <span><b>Value</b></span>
                </div>
            </div>
            @foreach ($globalConfigurations as $globalConfig)
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <input type="text" class="form-control" id="{{$globalConfig->key}}" name="keys[]" value="{{$globalConfig->key}}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        @if ($globalConfig->type == 'text')
                            <div class="form-group mb-3">
                                <input type="text" class="form-control" id="{{$globalConfig->key}}_value" name="values[]" value="{{$globalConfig->value}}">
                            </div>
                        @elseif ($globalConfig->type == 'number')
                            <div class="form-group mb-3">
                                <input type="number" class="form-control" id="{{$globalConfig->key}}_value" name="values[]" step="0.01" value="{{$globalConfig->value}}">
                            </div>
                        @elseif ($globalConfig->type == 'select-status-task')
                            <div class="form-group">
                                <select class="form-select" id="{{$globalConfig->key}}_value" name="values[]">
                                    @foreach ($taskStatuses as $status)
                                        <option value="{{$status->id}}" @if($globalConfig->value == $status->id) selected @endif>{{$status->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        @elseif ($globalConfig->type == 'select-status-project')
                            <div class="form-group">
                                <select class="form-select" id="{{$globalConfig->key}}_value" name="values[]">
                                    @foreach ($projectStatuses as $status)
                                        <option value="{{$status->id}}" @if($globalConfig->value == $status->id) selected @endif>{{$status->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        @elseif ($globalConfig->type == 'select-status-invoice')
                            <div class="form-group">
                                <select class="form-select" id="{{$globalConfig->key}}_value" name="values[]">
                                    @foreach ($invoiceStatuses as $status)
                                        <option value="{{$status->id}}" @if($globalConfig->value == $status->id) selected @endif>{{$status->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach

            <div class="row mt-4">
                <div class="col-md-12 text-end">
                    <a class="btn btn-outline-primary" href="{{route('dashboard.leaves.index')}}"><span class="px-2">{{__('global.cancel')}}</span></a>
                    <button class="btn btn-primary"><span class="px-5">{{__('global.save')}} {{__('crud.globalConfigurations.title_singular')}}</span></button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
