@extends('layouts.dashboard', ['section' => 'Documents'])

@section('content')
    <div class="row mt-2">
        <div class="col-md-8">
            <span class="h2 d-inline-block mt-1">
                <b>{{__('crud.documents.personal_folders')}}</b> <span class="text-muted" id="folderCounter">({{ count($folders) }})</span>
            </span>

            <button type="button" class="btn btn-primary d-inline-block ms-3 align-top" data-bs-toggle="modal"
                data-bs-target="#createFolderModal">
                <span class="px-4"><i class='bx bx-folder-plus mt-1'></i> {{__('crud.documents.add_folder')}}</span>
            </button>
        </div>
        <div class="col-md-4 text-center d-flex flex-column align-items-end mt-2">
            <p class="mb-0">{{__('global.space_used')}}: {{ $portal->storage_used }} GB / {{ $portal->storage_size }} GB</p>
            <div class="progress" style="width: 12rem">
                <div class="progress-bar {{ $portal->percentage_used > 85 ? 'bg-danger' : 'bg-success' }}"
                    role="progressbar" style="width: {{ $portal->percentage_used }}%;" aria-valuenow="80" aria-valuemin="0"
                    aria-valuemax="100"></div>
            </div>
        </div>
    </div>

    <div class="row mt-4" id="foldersContainer">
        @include('partials.documentFolders.list', ['folders' => $folders])
    </div>

    <div class="modal fade" id="createFolderModal" tabindex="-1" aria-labelledby="createFolderModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createFolderModalLabel">{{__('global.create')}} {{__('crud.documents.fields.folder')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('dashboard.documents.store') }}" method="POST" id="storeFolderFrm">
                        @csrf
                        <div class="mb-3">
                            <input type="text" class="form-control" id="folderName" name="folderName"
                                value="Carpeta sin título" required>
                        </div>

                        <div class="row">
                            <div class="col-md-12 text-end">
                                <button type="button" class="btn btn-secondary me-2"
                                    data-bs-dismiss="modal">{{__('global.cancel')}}</button>
                                <button type="submit" class="btn btn-primary">{{__('global.create')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('#createFolderModal').on('shown.bs.modal', function() {
            $('#folderName').focus();
            $('#folderName').select();
        })

        $('#storeFolderFrm').submit(function(e) {
            e.preventDefault();

            let folderName = $('#folderName').val();

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    name: folderName
                },
                dataType: 'json',
                success: function(response) {
                    reloadFolders();
                    $('#createFolderModal').modal('hide');
                    $('#storeFolderFrm').reset();
                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        title: 'Folder created successfully',
                        showConfirmButton: false,
                        position: 'top-end',
                        timer: 3000
                    });
                }
            });
        });

        $(document).on('click', '.folder-card', function() {
            if ($(event.target).closest('.btn-options').length > 0 ||
                $(event.target).closest('.dropdown-item').length > 0) {

                return;
            }
            window.location.href = $(this).data('href');
        });

        setDeleteAction();

        const foldersContainer = document.getElementById('foldersContainer');
        var listDrake = dragula([foldersContainer], {
            direction: 'horizontal'
        });
        listDrake.on('drop', function(el) {
            var url =
                "{{ route('dashboard.documents.update-order', ['folder' => ':folder']) }}";

            // get children with id starting with list-container
            var lists = foldersContainer.querySelectorAll('.folder-card-item');
            for (var i = 0; i < lists.length; i++) {
                var list = lists[i];
                var folderId = list.dataset.folderid;
                var order = Array.from(lists).indexOf(list);

                $.ajax({
                    url: url.replace(':folder', folderId) + '?order=' + order,
                    type: 'GET',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {},
                    error: function(response) {
                        Swal.fire({
                            toast: true,
                            title: response.message,
                            icon: 'error',
                            showConfirmButton: false,
                            position: 'top-end',
                            timer: 3000
                        });
                    }
                });
            }
        });

        function reloadFolders() {
            $.ajax({
                url: '{{ route('dashboard.documents.index') }}',
                type: 'GET',
                success: function(response) {
                    $('#foldersContainer').html(response);
                    $('#folderCounter').text('(' + $('.folder-card').length + ')');
                    setDeleteAction();
                }
            });
        }

        function setDeleteAction() {
            $('.submitDeleteFrm').click(function(e) {
                e.preventDefault();
                let url = "{{ route('dashboard.documents.destroy', ['document' => ':document']) }}";

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action will delete all documents inside this folder!",
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
                            url: url.replace(':document', $(this).data('folderid')),
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                _method: 'DELETE'
                            },
                            dataType: 'json',
                            success: function(response) {
                                e.preventDefault();
                                reloadFolders();
                                Swal.fire({
                                    toast: true,
                                    icon: 'success',
                                    title: 'Folder deleted successfully',
                                    showConfirmButton: false,
                                    position: 'top-end',
                                    timer: 3000
                                });
                            },
                            error: function(response) {
                                e.preventDefault();
                                reloadFolders();
                                Swal.fire({
                                    toast: true,
                                    icon: 'success',
                                    title: 'Folder deleted successfully',
                                    showConfirmButton: false,
                                    position: 'top-end',
                                    timer: 3000
                                });
                            }
                        });
                    }
                });
            });
        }
    </script>
@endsection
