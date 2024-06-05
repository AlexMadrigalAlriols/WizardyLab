@extends('layouts.dashboard', ['section' => 'Projects'])

@section('content')
    <div class="mt-2">
        <span class="h2 d-inline-block mt-1">
            <b>Projects</b><span class="text-muted">({{count($projects)}})</span>
        </span>
        <a class="btn btn-primary d-inline-block ms-3 align-top" href="{{route('dashboard.projects.create')}}">
            <span class="px-4"><i class="bx bx-plus mt-1"></i>Add new Project</span>
        </a>
    </div>

    <div class="table-actions">
        <div class="row">
            <div class="col-md-5">

            </div>
            <div class="col-md-7 mt-1">
                <div class="justify-content-end">
                    <div class="row">
                        <div class="col-md-1 offset-md-3"></div>
                        <div class="col-md-6 col-sm-12 mt-3">
                            <div class="search-container w-100">
                                <i class="bx bx-search search-icon"></i>
                                <input type="text" class="search-input" id="search-input" name="search_input"
                                    placeholder="Search Projects">
                            </div>
                        </div>
                        <div class="col-md-2 col-12 mt-3 text-end">
                            <button class="btn btn-change-view active" data-bs-toggle="tooltip" data-bs-title="List View"
                                data-bs-placement="top">
                                <i class='bx bx-list-ul'></i>
                            </button>
                            <button class="btn btn-change-view" data-bs-toggle="tooltip" data-bs-title="Board View"
                                data-bs-placement="top">
                                <i class='bx bx-chalkboard'></i>
                            </button>
                            <button class="btn btn-change-view" data-bs-toggle="tooltip" data-bs-title="Card View"
                                data-bs-placement="top">
                                <i class='bx bxs-dashboard'></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive mt-5">
        <table class="table table-borderless table-hover">
            <thead class="border-top border-bottom">
                <tr>
                    <th scope="col" class="min-width-0"></th>
                    <th scope="col">CODE</th>
                    <th scope="col">TITLE</th>
                    <th scope="col">START DATE</th>
                    <th scope="col">DUE DATE</th>
                    <th scope="col">STATUS</th>
                    <th scope="col">HOURS LOGGED</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($projects as $project)
                    <tr class="table-entry align-middle border-bottom">
                        <td class="text-nowrap">
                        </td>
                        <td><b>{{ $project->code }}</b></td>
                        <td><a href="{{route('dashboard.projects.board', $project->id)}}" class="text-decoration-none"><b>{{ $project->name }}</b></a></td>
                        <td class="text-muted">
                            {{ $project->start_date ? $project->start_date->format('d/m/Y') : '-' }}
                        </td>
                        <td class="{{ $project->is_overdue ? 'text-danger' : 'text-muted' }}">{{ $project->deadline ? $project->deadline->format('d/m/Y') : '-' }}</td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm dropdown-toggle" style="{{ $project->status->styles }}" data-bs-toggle="dropdown" aria-expanded="false">
                                  <b>{{$project->status->title}}</b>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-sm">
                                    @foreach ($statuses as $status)
                                        <li class="border-top"><a class="dropdown-item" href="{{route('dashboard.projects.update-status', [$project->id, $status->id])}}"><span class="badge" style="{{$status->styles}}">{{$status->title}}</span></a></li>
                                    @endforeach
                                </ul>
                              </div>
                        </td>
                        <td class="text-muted"><span class="{{$project->total_hours > $project->limit_hours ? 'text-danger' : ''}}">{{$project->total_hours}}h</span> / <b>{{$project->limit_hours ?? '-'}}h</b> </td>
                        <td class="text-center">
                            <div class="dropdown">
                                <button class="btn btn-options" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class='bx bx-dots-horizontal-rounded'></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li><a class="dropdown-item" href="{{route('dashboard.projects.show', $project->id)}}"><i class='bx bx-show' ></i> View</a></li>
                                    <li><a class="dropdown-item" href="{{route('dashboard.projects.edit', $project->id)}}"><i class='bx bx-edit' ></i> Edit</a></li>
                                    <li><a class="dropdown-item" href="{{route('dashboard.projects.generate-invoice', $project->id)}}"><i class='bx bx-file' ></i> Generate Invoice</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{route('dashboard.projects.destroy', $project->id)}}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="dropdown-item text-danger"><i class='bx bx-trash' ></i> Remove</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach

                @if(!count($projects))
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <span class="text-muted">No Projects found!</span>
                        </td>
                    </tr>
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="9" class="py-4 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                {{ count($projects) }} to {{$pagination['take'] ?? count($projects)}} items of <b>{{ $total }}</b>
                                @if(request('page') != 'all')
                                    <span class="ms-4">
                                        <a href="?page=all" class="text-decoration-none">View All <i class='bx bx-chevron-right'></i></a>
                                    </span>
                                @endif
                            </div>
                            <div>
                                @if(request('page') != 'all')
                                    <ul class="pagination m-0">
                                        <li class="page-item {{ $pagination['pages'] > 1 && request('page', 1) > 1 ? '' : 'disabled'}}">
                                            <a class="page-link arrow" href="?page={{(request('page', 1) - 1) . (request('status') ? '&status=' . request('status') : '')}}"><i class='bx bx-chevron-left' ></i></a>
                                        </li>
                                        @for ($page = 1; $page <= $pagination['pages']; $page++)
                                            <li class="page-item" aria-current="page">
                                                <a class="page-link {{request('page', 1) == $page ? 'active' : ''}}" href="?page={{$page . (request('status') ? '&status=' . request('status') : '')}}"><b>{{$page}}</b></a>
                                            </li>
                                        @endfor
                                        <li class="page-item {{ $pagination['pages'] > 1 && request('page', 1) != $pagination['pages'] ? '' : 'disabled'}}">
                                            <a class="page-link arrow" href="?page={{(request('page', 1) + 1) . (request('status') ? '&status=' . request('status') : '')}}"><i class='bx bx-chevron-right'></i></a>
                                        </li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.table-responsive').on('show.bs.dropdown', function() {
                $('.table-responsive').css("overflow", "inherit");
            });

            $('.table-responsive').on('hide.bs.dropdown', function() {
                $('.table-responsive').css("overflow", "auto");
            })
        });
    </script>
@endsection
