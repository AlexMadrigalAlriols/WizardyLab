@extends('layouts.dashboard', ['section' => 'Items'])

@section('content')

<div class="row">
    <div class="col-md-8 col-sm-12">
        <div class="mt-1">
            <span class="h2 d-inline-block mt-1">
                <b>Edit {{$inventory -> name}} <span class="text-muted">({{$inventory -> reference}})</span></b>
            </span>
        </div>
        <form action="{{route('dashboard.inventories.update', $inventory->id)}}" method="POST" class="mt-2 pb-3">
            @csrf
            @method("put")
            @include('partials.inventories.form')
            <div class="row mt-4">
                <div class="col-md-10 text-end">
                    <a class="btn btn-outline-primary" href="{{route('dashboard.inventories.index')}}"><span class="px-2">Cancel</span></a>
                    <button class="btn btn-primary ms-2"><span class="px-5">Edit item</span></button>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-4 px-3 border-start">

            <div class="mt-2 border-bottom">
                <p class="h4 d-inline-block mt-1">
                    <b>Files</b>
                </p>
                <div class="row">
                <div class="col-md-6 p-1">
                    <div style="display: flex; flex-wrap:wrap;">
                        @foreach ($inventory->inventory_files->take(ceil($inventory->inventory_files->count()/2)) as $file)
                                <a style="z-index:200; width: 100%" class="text-decoration-none" href="{{route("dashboard.inventories.delete_file", $file->id)}}">
                                    <div style="height:{{mt_rand(200, 400)}}px; width: 100%; border-radius:10px" class="galery-item border m-1">
                                                <img style="height:100%; width:100%; object-fit:cover; border-radius:10px" src="{{ asset('storage/' . $file->path) }}" class="img-fluid" alt="{{$file->title}}" style="max-width: 250px">
                                            <div class="trash-icon text-decoration-none" style="color: black"><i class='bx bx-trash'></i></div>
                                    </div>
                                </a>

                        @endforeach
                    </div>
                </div>
                <div class="col-md-6 p-1">
                    <div style="display: flex; flex-wrap:wrap;">
                        @foreach ($inventory->inventory_files->skip(ceil($inventory->inventory_files->count()/2)) as $file)
                            <a style="z-index:200; width: 100%" class="text-decoration-none" href="{{route("dashboard.inventories.delete_file", $file->id)}}">
                                    <div style="height:{{mt_rand(200, 400)}}px; width: 100%; border-radius:10px" class="galery-item borde m-1">

                                                <img style="height:100%; width:100%; object-fit:cover; border-radius:10px" src="{{ asset('storage/' . $file->path) }}" class="img-fluid" alt="{{$file->title}}" style="max-width: 250px">
                                        <div class="trash-icon" style="color:black;"><i class='bx bx-trash'></i></div>
                                    </div>
                            </a>
                        @endforeach
                    </div>
                </div>
                @if($inventory->inventory_files->isEmpty())
                    <div class="text-center p-3">
                        <p class="text-muted">No files uploaded yet!</p>
                    </div>
                @endif
            </div>
        </div>
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

    </script>

@endsection
