@extends('layouts.dashboard', ['section' => 'Expenses'])

@section('content')

<div class="row">
    <div class="col-md-8 col-sm-12">
        <div class="mt-1">
            <span class="h2 d-inline-block mt-1">
                <b>Create a Expense</b>
            </span>
        </div>
        <form action="{{route('dashboard.expenses.store')}}" method="POST" class="mt-2 pb-3">
            @csrf
            @include('partials.expenses.form')
            <div class="row mt-4">
                <div class="col-md-12 text-end">
                    <a class="btn btn-outline-primary" href="{{route('dashboard.expenses.index')}}"><span class="px-2">Cancel</span></a>
                    <button class="btn btn-primary ms-2"><span class="px-5">Create expense</span></button>
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
