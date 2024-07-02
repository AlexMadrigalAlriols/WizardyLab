@foreach($documents ?? [] as $document)
@php
    if (strlen($document->title) > 45) {
        $document->title = substr($document->title, 0, 45) . '...';
    }
@endphp
<div class="col-md-9 offset-md-1 col-sm-12 mt-3">
    <span class="card folder-card text-decoration-none text-dark" data-documentid="{{$document->id}}" href="{{route('dashboard.documents.list', $document->id)}}">
        <div class="card-body">
            <div class="row">
                <div class="col-1 text-center">
                    <span>
                        <i class='{{$document->icon}} text-muted mt-2' style="font-size: 30px;"></i>
                    </span>
                </div>
                <div class="col-7 col-sm-9">
                    <span class="align-middle h5 ms-1"><b>{{$document->title}}</b></span>
                    <p class="text-muted mb-0 ms-1" style="font-size: 14px;">{{$document->created_at->format('jS M, Y')}} - Subido por {{$document->userUpload->name}}</p>
                </div>
                <div class="col-4 col-sm-2 text-end">
                    @if ($document->data['needSigned'])
                        <span class="badge {{ isset($document->data['signed']) && $document->data['signed'] ? 'bg-success' : 'bg-danger'}} align-middle me-3">
                            {{ isset($document->data['signed']) && $document->data['signed'] ? 'Signed' : 'Non-Signed'}}
                        </span>
                    @endif
                    <div class="dropdown d-inline-block mt-1">
                        <button class="btn btn-options" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class='bx bx-dots-horizontal-rounded' style="font-size: 14px;"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li><a class="dropdown-item" href="{{route('dashboard.documents.download', [$folder->id, $document->id])}}"><i class='bx bxs-download' ></i> Download</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form class="deleteDocumentFrm" action="{{route('dashboard.documents.destroy-document', [$folder->id, $document->id])}}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="dropdown-item text-danger">
                                        <i class='bx bx-trash' ></i> Delete
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </span>
</div>
@endforeach

@if(count($documents) == 0)
<div class="col-md-12 mt-5 text-center">
    @include('images.no_files_found')
    <p class="text-muted mt-4">No documents here</p>
</div>
@endif

