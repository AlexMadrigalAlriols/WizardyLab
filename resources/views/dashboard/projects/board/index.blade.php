@extends('layouts.dashboard', ['section' => 'Projects'])

@section('styles')
    <style>
        .height-100 {
            height: calc(100vh - 10rem) !important;
        }
    </style>
@endsection
@section('content_with_padding')
    @include('partials.board.menu')
@endsection

@section('content')
    {{-- kanban board --}}
    <div class="kanban-board" id="kanban-board">
        @foreach ($statuses as $status)
            @include('partials.projects.board.list', [
                'tasks' => $tasks->where('status_id', $status->status->id),
                'status' => $status,
            ])
        @endforeach
    </div>
@endsection

<!-- Modal for create task -->
<div class="modal fade" id="showTaskModal" tabindex="-1" aria-labelledby="showTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-fullscreen-md-down">
        <div class="modal-content overflow-hidden d-flex">
        </div>
    </div>
</div>
@section('scripts')
    <script>
        $(document).ready(function() {
            // Configuración de Dragula para mover tareas dentro de las listas
            const taskContainers = [
                @foreach ($statuses as $status)
                    document.getElementById('list-{{ $status->status->id }}'),
                @endforeach
            ];

            var taskDrake = dragula(taskContainers, {
                moves: function(el, source, handle, sibling) {
                    return el.classList.contains('card-task');
                },
                direction: 'vertical',
                mirrorContainer: document.body,
                invalid: function(el, handle) {
                    return handle.classList.contains('list-container');
                }
            });

            taskDrake.on('drop', function(el) {
                var url =
                    "{{ route('dashboard.tasks.update-status', ['task' => ':task', 'status' => ':status']) }}";
                var statusId = el.parentElement.id.split('-')[1];

                // foreach task send ajax request to update the order
                var tasks = el.parentElement.children;
                for (var i = 0; i < tasks.length; i++) {
                    var task = tasks[i];
                    var taskId = task.dataset.taskId;
                    var order = Array.from(tasks).indexOf(task);

                    $.ajax({
                        url: url.replace(':task', taskId).replace(':status', statusId) + '?order=' +
                            order,
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

                updateCounters();
            });

            // Configuración de Dragula para mover listas dentro del tablero
            const kanbanBoard = document.getElementById('kanban-board');
            var listDrake = dragula([kanbanBoard], {
                direction: 'horizontal',
                moves: function(el, source, handle, sibling) {
                    return el.classList.contains('list-container');
                },
                invalid: function (el, handle) {
                    // Evita que se arrastren elementos dentro de las listas
                    return handle.classList.contains('card-task-body') || handle.classList.contains('card-task-body-text') || handle.classList.contains('card-task')
                        || handle.classList.contains('card-task-header-title') || handle.classList.contains('badge') || handle.classList.contains('text-danger')
                        || handle.classList.contains('bx-play-circle') || handle.classList.contains('btn-attendance-task') || handle.classList.contains('bx-stop-circle')
                        || handle.classList.contains('task-body-grab') || handle.classList.contains('bx-calendar-x') || handle.classList.contains('bx-paperclip')
                        || handle.classList.contains('img-fluid') || handle.classList.contains('row');
                }
            });

            listDrake.on('drop', function(el) {
                var url =
                    "{{ route('dashboard.projects.update-order', ['projectStatus' => ':projectStatus']) }}";

                // get children with id starting with list-container
                var lists = kanbanBoard.querySelectorAll('.list-container');
                for (var i = 0; i < lists.length; i++) {
                    var list = lists[i];
                    var statusId = list.dataset.statusid;
                    var order = Array.from(lists).indexOf(list);

                    $.ajax({
                        url: url.replace(':projectStatus', statusId) + '?order=' +
                            order,
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
        });

        function submitCommentForm() {
            var form = $('#commentsFrm');
            var url = form.attr('action');
            var data = form.serialize();

            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                success: function(response) {
                    $('#comments').prepend(response);

                    Swal.fire({
                        toast: true,
                        title: 'Comment added successfully',
                        icon: 'success',
                        showConfirmButton: false,
                        position: 'top-end',
                        timer: 3000
                    });
                },
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

        function deleteComment(e) {
            var form = $(e).closest('form');
            var url = form.attr('action');
            var data = form.serialize();

            $.ajax({
                url: url,
                type: 'DELETE',
                data: data,
                success: function(response) {
                    form.closest('.comment-card').remove();

                    Swal.fire({
                        toast: true,
                        title: 'Comment deleted successfully',
                        icon: 'success',
                        showConfirmButton: false,
                        position: 'top-end',
                        timer: 3000
                    });
                },
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

        function updateCounters() {
            var counters = document.querySelectorAll('.card-title span.badge');
            counters.forEach(function(counter) {
                var statusId = counter.parentElement.parentElement.parentElement.querySelector('.task-container').id
                    .split('-')[1];
                var tasks = document.querySelectorAll('.task-container#list-' + statusId + ' .card-task');
                counter.innerText = tasks.length;
            });
        }

        function openShowTaskModal(taskId) {
            $('#showTaskModal').find('.modal-content').load("{{ route('dashboard.tasks.show', ':task') }}".replace(':task',
                taskId), function() {
                $('#showTaskModal').modal('show');
            });
        }

        function sendAction(action, taskId) {
            var url = "{{ route('dashboard.tasks.action', ['task' => ':task', 'action' => ':action']) }}";

            $.ajax({
                url: url.replace(':task', taskId).replace(':action', action),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    window.location.href = response.redirect;
                },
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

        function switchListCollapse(event) {
            var url =
                    "{{ route('dashboard.projects.update-order', ['projectStatus' => ':projectStatus']) }}";
            $(event).closest('.list-container').toggleClass('collapsed');

            var statusId = $(event).closest('.list-container').data('statusid');
            var collapsed = $(event).closest('.list-container').hasClass('collapsed');

            $.ajax({
                url: url.replace(':projectStatus', statusId) + '?collapsed=' + collapsed,
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
    </script>
@endsection
