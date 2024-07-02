@extends('layouts.dashboard', ['section' => 'Documents'])

@section('styles')
<style>
    .dropzone {
        border: 2px dashed #cccccc;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        color: #cccccc;
        font-size: 24px;
        transition: background-color 0.3s;
        height: 100%;
        display: none; /* Oculto por defecto */
        align-items: center;
        justify-content: center;
        position: absolute;
        top: 0;
        left: 0;
        width: calc(100% - 17rem);
        z-index: 9999;
        background-color: rgba(0, 0, 0, 0.5);
        margin-left: 16rem;
        margin-top: 1rem;
    }

    .dropzone.dragover {
        background-color: rgba(238, 238, 238, 0.8);
    }

    .modal.full-screen-modal .modal-dialog {
        width: calc(100vw - 30px);
        height: calc(100vh - 30px);
        margin: 10px;
        padding: 0;
        max-width: calc(100% - 30px);
    }

    .modal.full-screen-modal .modal-content {
        height: 100% !important;
        border-radius: 0;
    }

    .modal.full-screen-modal .modal-body {
        overflow-y: auto;
    }
</style>
@endsection
@section('content')
    <div class="mt-2">
        <a href="{{route('dashboard.documents.index')}}" class="btn btn-outline-primary d-inline-block me-3 align-top mt-1">
            <span class="px-1"><</span>
        </a>

        <span class="h2 d-inline-block mt-1">
            <b>{{$folder->name}}</b> <span class="text-muted" id="documentCounter">({{count($documents)}})</span>
        </span>

        <button class="btn btn-primary d-inline-block ms-3 align-top" id="addFile">
            <span class="px-4"><i class="bx bx-plus mt-1"></i>Add file</span>
        </button>
    </div>

    <div class="row mt-4">
        <div class="col-md-9 offset-md-1 col-sm-12 ">
            <div class="search-container w-100">
                <i class="bx bx-search search-icon"></i>
                <input type="text" class="search-input" id="search-input" name="search_input"
                    placeholder="Search Document">
            </div>
        </div>
    </div>

    <div class="row mt-2 pb-4" id="documentContainer">
        @include('partials.documents.list', ['documents' => $documents])
    </div>

    <div id="dropzone" class="dropzone">
        Drag and drop files anywhere on the screen to upload
    </div>

    <!-- Modal for selecting user -->
    <div class="modal fade" id="selectUserModal" tabindex="-1" aria-labelledby="selectUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="selectUserModalLabel">Select User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('dashboard.documents.assign-file', $folder->id)}}" id="documentUserFrm" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="userSelect" class="form-label">Select a user:</label>
                            <select class="form-select" id="userSelect" name="user_id">
                                <option value="">All users</option>
                                @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" disabled name="needSigned" id="needSigned">
                                <label class="form-check-label mt-1 ms-2" for="needSigned">Need to be signed</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for selecting user -->
    <div class="modal fade full-screen-modal" id="showDocument" tabindex="-1" aria-labelledby="showDocumentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body" id="documentViewerContainer"></div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/pdf-lib/dist/pdf-lib.min.js"></script>
<script>
    Dropzone.autoDiscover = false;
    const dropzoneElement = document.getElementById('dropzone');

    $('.btn-options').click(function(e) {
        e.stopPropagation();
    });

    // Evitar la propagación del evento clic en los elementos del menú desplegable
    $('.dropdown-item').click(function(e) {
        e.stopPropagation();
    });

    const dropzone = new Dropzone(document.body, {
        url: '{{route('dashboard.documents.upload-file', $folder->id)}}', // Cambia esta URL a tu endpoint de subida de archivos
        paramName: 'dropzone_image',
        maxFilesize: 2,
        acceptedFiles: '.jpeg,.jpg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.txt',
        previewsContainer: false,
        clickable: true,
        uploadMultiple: true,
        headers: {
            'X-CSRF-TOKEN': '{{csrf_token()}}'
        },
        init: function() {
            const showDropzone = () => {
                dropzoneElement.style.display = 'flex';
            };
            const hideDropzone = () => {
                dropzoneElement.style.display = 'none';
            };

            this.on('dragenter', function(event) {
                const types = event.dataTransfer.types;
                if (types && Array.from(types).some(type => type === 'Files')) {
                    showDropzone();
                }
            });

            this.on('dragleave', function(event) {
                if (!event.relatedTarget || !dropzoneElement.contains(event.relatedTarget)) {
                    hideDropzone();
                }
            });

            this.on('drop', function() {
                dropzoneElement.classList.remove('dragover');
                hideDropzone();
            });

            this.on('success', function(file, response) {
                hideDropzone();
                $('#documentUserFrm').trigger('reset');
                $('#selectUserModal').modal('show');
            });

            this.on('error', function(file, response) {
                Swal.fire({
                    toast: true,
                    icon: 'error',
                    title: response.message,
                    showConfirmButton: false,
                    position: 'top-end',
                    timer: 3000
                });
            });
        }
    });

    $('#documentUserFrm').submit(function (e) {
        e.preventDefault();

        let userId = $('#userSelect').val();

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: {
                _token: '{{csrf_token()}}',
                user_id: userId,
                needSigned: $('#needSigned').is(':checked') ? 1 : 0
            },
            success: function (response) {
                $('#selectUserModal').modal('hide');
                reloadDocuments();
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    title: 'File uploaded successfully',
                    showConfirmButton: false,
                    position: 'top-end',
                    timer: 3000
                });
            }
        });
    });
    $('.deleteDocumentFrm').submit(function (e) {
        e.preventDefault();

        Swal.fire({
            title: 'Are you sure?',
            text: "This action will delete permanently the document!",
            icon: 'warning',
            showCancelButton: true,
            customClass: {
                confirmButton: 'confirm-btn'
            },
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: {
                        _token: '{{csrf_token()}}',
                        _method: 'DELETE'
                    },
                    success: function (response) {
                        reloadDocuments();
                        Swal.fire({
                            toast: true,
                            icon: 'success',
                            title: 'Document deleted successfully',
                            showConfirmButton: false,
                            position: 'top-end',
                            timer: 3000
                        });
                    }
                });
            }
        });
    });
    $('#addFile').click(function () {
        dropzone.hiddenFileInput.click();
    });
    $(document).on('click', '.folder-card', function () {
        if ($(event.target).closest('.btn-options').length > 0 ||
            $(event.target).closest('.dropdown-item').length > 0) {

            return;
        }

        $('#documentViewerContainer').html('');
        let documentId = $(this).data('documentid');
        let url = '{{route('dashboard.documents.view', [$folder->id, "document" => ":document"])}}';
        $.ajax({
            url: url.replace(':document', documentId),
            success: function (response) {
                $('#documentViewerContainer').html(response);
                $('#showDocument').modal('show');
            }
        });
    });
    $('#search-input').keyup(function () {
        let search = $(this).val();

        $.ajax({
            url: '{{route('dashboard.documents.list', $folder->id)}}',
            data: {
                search: search
            },
            success: function (response) {
                $('#documentContainer').html(response);
                $('#documentCounter').text('(' + $('.folder-card').length + ')');
            }
        });
    });
    $('#userSelect').change(function () {
        if($(this).val() == '') {
            $('#needSigned').prop('checked', false);
            $('#needSigned').prop('disabled', true);
        } else {
            $('#needSigned').prop('disabled', false);
        }
    });

    function submitSaveDocumentForm() {
        $.ajax({
            url: $('#updateDocFrm').attr('action'),
            type: 'PUT',
            data: $('#updateDocFrm').serialize(),
            success: function (response) {
                $('#editDocumentTitle').text($('#updateDocFrm input[name="title"]').val());
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    title: 'Document updated successfully',
                    showConfirmButton: false,
                    position: 'top-end',
                    timer: 1000
                });
                reloadDocuments();
            }
        });
    }

    function reloadDocuments() {
        $.ajax({
            url: '{{route('dashboard.documents.list', $folder->id)}}',
            success: function (response) {
                $('#documentContainer').html(response);
                $('#documentCounter').text('(' + $('.folder-card').length + ')');
            }
        });
    }
</script>
@endsection
