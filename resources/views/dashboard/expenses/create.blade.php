@extends('layouts.dashboard', ['section' => 'Expenses'])

@section('content')

<div class="row">
    <div class="col-md-8 col-sm-12">
        <div class="mt-1">
            <span class="h2 d-inline-block mt-1">
                <b>{{__('global.create')}} {{__('crud.expenses.title_singular')}}</b>
            </span>
        </div>
        <form action="{{route('dashboard.expenses.store')}}" method="POST" class="mt-2 pb-3">
            @csrf
            @include('partials.expenses.form')
            <div class="row mt-4">
                <div class="col-md-12 text-end">
                    <a class="btn btn-outline-primary" href="{{route('dashboard.expenses.index')}}"><span class="px-2">{{__('global.cancel')}}</span></a>
                    <button class="btn btn-primary ms-2"><span class="px-5">{{__('global.create')}} {{__('crud.expenses.title_singular')}}</span></button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        Dropzone.autoDiscover = false;

        $(document).ready(function() {
            generateDropZone(
                "#billFileDropZone",
                "{{ route('dashboard.expenses.upload_file') }}",
                "{{ csrf_token() }}",
                true,
                true
            );
        });
    </script>

@endsection
