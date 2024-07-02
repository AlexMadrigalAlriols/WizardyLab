@foreach($folders as $folder)
    <div class="col-md-3 mt-3 folder-card-item" data-folderid="{{$folder->id}}">
        <a class="card folder-card text-decoration-none text-dark" href="{{route('dashboard.documents.list', strtolower(urlencode($folder->name)))}}">
            <div class="card-body">
                <div class="row">
                    <div class="col-2">
                        <span><i class='bx bx-folder text-muted' style="font-size: 30px;"></i></span>
                    </div>
                    <div class="col-8">
                        <span class="align-middle h5"><b>{{$folder->name}}</b></span>
                    </div>
                    <div class="col-2">
                        <div class="dropdown">
                            <button class="btn btn-options" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class='bx bx-dots-horizontal-rounded' style="font-size: 14px;"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li>
                                    <form class="deleteFolderFrm" action="{{route('dashboard.documents.destroy', $folder->id)}}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="dropdown-item text-danger submitDeleteFrm" type="button" data-folderid="{{$folder->id}}">
                                            <i class='bx bx-trash' ></i> Delete
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
@endforeach

@if(count($folders) == 0)
    <div class="col-md-12 mt-5 text-center">
        @include('images.no_folders_found')
        <p class="text-muted mt-3">No folders found here!</p>
    </div>
@endif
