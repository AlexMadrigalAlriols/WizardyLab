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
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <span class="h2">
                            <a href="{{ route('dashboard.projects.board', $project->id) }}" class="btn btn-outline-primary">
                                <span class="px-2"><</span>
                            </a>
                            Rules
                        </span>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="{{ route('dashboard.automation.create', $project->id) }}"
                            class="btn btn-primary">Create Rule</a>
                        <div class="dropdown d-inline-block ms-2">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="filterButton" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class='bx bx-filter-alt' ></i> All
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="filterButton">
                                <li><a class="dropdown-item" href="#" onclick="changeFilter('All')">All</a></li>
                                <li><a class="dropdown-item" href="#" onclick="changeFilter('Enabled')">Enabled</a></li>
                                <li><a class="dropdown-item" href="#" onclick="changeFilter('Disabled')">Disabled</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <hr>
                @if (!count($boardRules))
                    <h5 class="card-title">Rules</h5>
                    <p class="card-text">No rules have been created yet.</p>
                    <a href="{{ route('dashboard.automation.create', $project->id) }}"
                        class="btn btn-primary">Create Rule</a>
                @endif

                @foreach ($boardRules as $rule)
                    @include('partials.board.rule', $rule)
                @endforeach
            </div>
        </div>
    </div>

    <div class="modal fade" id="addRuleToBoardModal" tabindex="-1" aria-labelledby="addRuleToBoardModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRuleToBoardModalLabel">Add Rule to Another Board</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="board_id" class="mb-2"><b>BOARD</b></label>
                    <select name="board" id="board_id" class="form-select">
                        @foreach ($boards as $board)
                            <option value="" disabled selected>Select board</option>
                            <option value="{{$board->id}}"><b>{{$board->name}}</b></option>
                        @endforeach
                    </select>

                    <button class="btn btn-primary mt-3" onclick="sendFormModal(this)"><i class='bx bx-plus-medical'></i> Add to board</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('[data-toggle="popover"]').popover({
                html: true
            });

            changeActive();
        });

        function changeActive() {

            $(".form-check-input").change(function() {
                var automation = $(this).data('id');
                var active = $(this).is(':checked');

                sendAjax(automation, {{$project->id}}, active);
            });
        }

        function changeFilter(filter) {
            $("#filterButton").html(`<i class='bx bx-filter-alt'></i> ${filter} `);

            $(".card-rule").each(function() {
                if (filter === 'All') {
                    $(this).show();
                } else if (filter === 'Enabled') {
                    if ($(this).find('.form-check-input').is(':checked')) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                } else if (filter === 'Disabled') {
                    if (!$(this).find('.form-check-input').is(':checked')) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                }
            });
        }

        function openModal(ruleId) {
            $('#addRuleToBoardModal').find('button').data('id', ruleId);
            $('#addRuleToBoardModal').modal('show');
        }

        function sendFormModal(button) {
            var automation = $(button).data('id');
            var projectId = $('#board_id').val();

            sendAjax(automation, projectId, true);
        }

        function sendAjax(automation, projectId, active) {
            var url = "{{ route('dashboard.automation.changeActive', ['project' => ':project_id', 'automation' => ':automation']) }}";

            $.ajax({
                url: url.replace(':automation', automation).replace(':project_id', projectId),
                data: {
                    _token: '{{ csrf_token() }}',
                    active: active
                },
                method: 'POST',
                success: function(data) {
                    $('#addRuleToBoardModal').modal('hide');
                    Swal.fire({
                        toast: true,
                        title: data.message,
                        icon: 'success',
                        showConfirmButton: false,
                        position: 'top-end',
                        timer: 3000
                    });

                    $('#switch-' + automation).prop('checked', active);
                }
            });
        }
    </script>
@endsection
