@extends('layouts.dashboard', ['section' => 'Items'])

@section('content')

<div class="row">
    <div class="col-md-8 col-sm-12">
        <div class="mt-1">
            <span class="h2 d-inline-block mt-1">
                <b>Create a item</b>
            </span>
        </div>
        <form action="{{route('dashboard.inventories.store')}}" method="POST" class="mt-2 pb-3">
            @csrf
            @include('partials.inventories.form')
            <div class="row mt-4">
                <div class="col-md-10 text-end">
                    <a class="btn btn-outline-primary" href="{{route('dashboard.inventories.index')}}"><span class="px-2">Cancel</span></a>
                    <button class="btn btn-primary ms-2" id="submitBtn"  disabled><span class="px-5">Create item</span></button>
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
                "#inventoryFileDropZone",
                "{{ route('dashboard.inventories.upload_file') }}",
                "{{ csrf_token() }}",
                true,
                true
            );
        });

        var obligatoryFields = ['name', 'reference', 'stock'];
        var limitedCharFields = ['name', 'reference'];

        $('input, select, textarea').each(function() {
            $(this).on('keyup', function() {
                checkObligatoryFields(obligatoryFields);
            });
        });

        countChars(limitedCharFields);

    </script>

@endsection
