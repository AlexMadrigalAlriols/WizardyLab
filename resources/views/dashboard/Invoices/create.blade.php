@extends('layouts.dashboard', ['section' => 'Invoices'])

@section('content')

<div class="row">
    <div class="col-md-8 col-sm-12">
        <div class="mt-1">
            <span class="h2 d-inline-block mt-1">
                <b>{{__('global.create')}} {{__('crud.invoices.title_singular')}}</b>
            </span>
        </div>
        <form action="{{route('dashboard.invoices.store')}}" method="POST" class="mt-2 pb-3">
            @include('partials.invoices.form')
            <div class="row mt-4">
                <div class="col-md-12 text-end">
                    <a class="btn btn-outline-primary" href="{{route('dashboard.invoices.index')}}"><span class="px-2">Cancel</span></a>
                    <button class="btn btn-primary ms-2"><span class="px-5">{{__('global.create')}} {{__('crud.invoices.title_singular')}}</span></button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $('#type').change(function() {
        if ($(this).val() == 'tasks') {
            $('#manual').addClass('d-none');
            $('#tasks').removeClass('d-none');
            $('#project').addClass('d-none');
        } else if ($(this).val() == 'projects') {
            $('#manual').addClass('d-none');
            $('#tasks').addClass('d-none');
            $('#project').removeClass('d-none');
        } else {
            $('#manual').removeClass('d-none');
            $('#tasks').addClass('d-none');
            $('#project').addClass('d-none');
        }
    })
</script>
@endsection
