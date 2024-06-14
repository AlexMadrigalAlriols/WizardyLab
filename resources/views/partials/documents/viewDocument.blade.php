<div class="row h-100">
    <div class="col-md-9 border-end h-100 text-center">
        @if ($document->mime_type == 'application/pdf')
            <iframe id="pdf" style="width: 100%; height: 100%;" src="{{ asset('storage/' . $document->path) }}"></iframe>
        @elseif($document->mime_type == 'image/png' || $document->mime_type == 'image/jpeg' || $document->mime_type == 'image/jpg' || $document->mime_type == 'image/gif')
            <img src="{{ asset('storage/' . $document->path) }}" alt="" class="img-fluid">
        @else
            <p>Can't show content!</p>
        @endif

    </div>
    <div class="col-md-3">
        <div class="modal-header">
            <h5 class="modal-title text-center" id="editDocumentTitle">{{$document->title}}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <h5><b>Editar documento</b></h5>
                <p class="text-muted">Rellena los detalles del documento</p>

                <form action="{{route('dashboard.documents.update-file', [$folder->id, $document->id])}}" method="PUT" id="updateDocFrm">
                    @csrf
                    <div class="form-group mt-2">
                        <label for="">Nombre del documento</label>
                        <input type="text" value="{{$document->title}}" name="title" class="form-control">
                    </div>

                    <div class="form-group mt-3">
                        <label for="">Carpeta de destino</label>
                        <select name="document_folder_id" id="document_folder_id" class="form-control form-select">
                            @foreach ($folders as $sfolder)
                                <option value="{{$sfolder->id}}" {{$sfolder->id == $document->document_folder_id ? 'selected' : ''}}>{{$sfolder->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <button class="btn btn-primary mt-4 w-100" onclick="submitSaveDocumentForm()" type="button"><i class='bx bx-save' ></i> Save document</button>
                </form>
            </div>
            @if (isset($document->data['needSigned']) && $document->data['needSigned'] === true)
                <div class="row mt-4">
                    <h5><b>Firma electrónica</b></h5>
                    @if($document->signed)
                        <p class="text-success">Este documento ha sido firmado</p>

                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 col-3">
                                        <img src="{{$document->user_signed->profile_img}}" alt="user_prof" class="rounded-circle img-fluid" style="max-width: 60px; max-height: 60px;">
                                    </div>
                                    <div class="col-md-6 col-6 align-middle">
                                        <div class="align-middle mt-1">
                                            <p class="mb-0"><b>{{$document->user_signed->name}}</b></p>
                                            <p class="text-muted">{{$document->user_signed->email}}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-3 text-center mt-3">
                                        <span class="badge bg-success">Signed</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-mute">Este documento se tiene que firmar digitalmente</p>

                        <a class="btn btn-primary w-100 mt-2" href="{{route('dashboard.documents.view-sign', [$folder->id, $document->id])}}"><i class='bx bx-pencil'></i> Sign Document</a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
