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
                            Configurations
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
                <div class="row">

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

@endsection
