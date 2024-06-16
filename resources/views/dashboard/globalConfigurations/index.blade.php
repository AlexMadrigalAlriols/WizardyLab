@extends('layouts.dashboard', ['section' => 'GlobalConfigurations'])

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="mt-1">
                <span class="h2 d-inline-block mt-1">
                    <b>{{ __('global.edit') }} Configuration</b>
                </span>
            </div>
            <form action="{{ route('dashboard.global-configurations.store') }}" method="POST" class="mt-2 pb-3">
                <div class="row">
                    <div class="col-md-5 mt-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mt-3">
                                    <input type="text" class="form-control @if($errors->has('name')) is-invalid @endif" maxlength="15" id="name" name="name" placeholder="{{__('crud.status.fields.name')}}" value="{{ old('name') ?? $portal->name }}">
                                    <label for="title">{{__('crud.status.fields.name')}} <span class="text-danger">*</span></label>
                                </div>

                                <div class="mt-0 text-end">
                                    <span class="text-muted"><span id="nameCountChar">0</span>/15</span>
                                </div>

                                @if ($errors->has('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mt-3">
                                    <input type="text" class="form-control @if($errors->has('subdomain')) is-invalid @endif" maxlength="8" id="subdomain" name="subdomain" placeholder="{{__('crud.status.fields.name')}}" value="{{ old('subdomain') ?? $portal->subdomain }}">
                                    <label for="subdomain">Subdomain <span class="text-danger">*</span></label>
                                </div>

                                <div class="mt-0 text-end">
                                    <span class="text-muted"><span id="subdomainCountChar">0</span>/8</span>
                                </div>

                                @if ($errors->has('subdomain'))
                                    <span class="text-danger">{{ $errors->first('subdomain') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="color" class="form-label">Primary Color</label>
                                <input type="text" name="primary_color" class="form-control colorpicker" id="primary_color" value="{{ $portal->data['primary_color'] ?? '#FFFFFF' }}" title="Choose your color">
                            </div>
                            <div class="col-md-6">
                                <label for="color" class="form-label">Secondary Color</label>
                                <input type="text" name="secondary_color" class="form-control colorpicker" id="secondary_color" value="{{ $portal->data['secondary_color'] ?? '#FFFFFF' }}" title="Choose your color">
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <label for="color" class="form-label">Menu Text Color</label>
                                <input type="text" name="menu_text_color" class="form-control colorpicker" id="menu_text_color" value="{{ $portal->data['menu_text_color'] ?? '#fff' }}" title="Choose your color">
                            </div>
                            <div class="col-md-6">
                                <label for="color" class="form-label">Text Color</label>
                                <input type="text" name="btn_text_color" class="form-control colorpicker" id="btn_text_color" value="{{ $portal->data['btn_text_color'] ?? '#FFFFFF' }}" title="Choose your color">
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <span class="h3">Logo</span><br>
                                <img src="{{$portal->logo}}" alt="actualLogo" width="200px" class="my-3">
                                <div class="dropzone mt-2" id="logoDropZone"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-7 mt-4">
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
                                        <input type="text" class="form-control" id="{{ $globalConfig->key }}"
                                            name="keys[]" value="{{ $globalConfig->key }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    @if ($globalConfig->type == 'text')
                                        <div class="form-group mb-3">
                                            <input type="text" class="form-control" id="{{ $globalConfig->key }}_value"
                                                name="values[]" value="{{ $globalConfig->value }}">
                                        </div>
                                    @elseif ($globalConfig->type == 'number')
                                        <div class="form-group mb-3">
                                            <input type="number" class="form-control" id="{{ $globalConfig->key }}_value"
                                                name="values[]" step="0.01" value="{{ $globalConfig->value }}">
                                        </div>
                                    @elseif ($globalConfig->type == 'select-status-task')
                                        <div class="form-group mb-3">
                                            <select class="form-select" id="{{ $globalConfig->key }}_value" name="values[]">
                                                @foreach ($taskStatuses as $status)
                                                    <option value="{{ $status->id }}"
                                                        @if ($globalConfig->value == $status->id) selected @endif>
                                                        {{ $status->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @elseif ($globalConfig->type == 'select-status-project')
                                        <div class="form-group mb-3">
                                            <select class="form-select" id="{{ $globalConfig->key }}_value"
                                                name="values[]">
                                                @foreach ($projectStatuses as $status)
                                                    <option value="{{ $status->id }}"
                                                        @if ($globalConfig->value == $status->id) selected @endif>
                                                        {{ $status->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @elseif ($globalConfig->type == 'select-status-invoice')
                                        <div class="form-group mb-3">
                                            <select class="form-select" id="{{ $globalConfig->key }}_value"
                                                name="values[]">
                                                @foreach ($invoiceStatuses as $status)
                                                    <option value="{{ $status->id }}"
                                                        @if ($globalConfig->value == $status->id) selected @endif>
                                                        {{ $status->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12 text-end">
                            <a class="btn btn-outline-primary me-2" href="{{ route('dashboard.index') }}"><span
                                    class="px-2">{{ __('global.cancel') }}</span></a>
                            <button class="btn btn-primary" disabled id="submitBtn"><span class="px-4">{{ __('global.save') }}
                                    Configuration</span></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script>
        var obligatoryFields = ['name', 'subdomain'];
        var limitedCharFields = ['name', 'subdomain'];
        Dropzone.autoDiscover = false;

        $(document).ready(function() {
            generateDropZone(
                "#logoDropZone",
                "{{ route('dashboard.global-configurations.upload_file') }}",
                "{{ csrf_token() }}",
                false,
                true
            );

            $('input, select, textarea').each(function() {
                $(this).on('keyup', function() {
                    checkObligatoryFields(obligatoryFields);
                });
                $(this).on('change', function() {
                    checkObligatoryFields(obligatoryFields);
                });
            });

            checkObligatoryFields(obligatoryFields)
            countChars(limitedCharFields);
checkObligatoryFields(obligatoryFields);
        });
    </script>
@endsection
