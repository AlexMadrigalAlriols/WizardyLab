@extends('layouts.dashboard', ['section' => 'Assignments'])

@section('content')
    <link rel="stylesheet" href="inventories.css">
    <div class="row h-100">
        <div class="col-md-11 border-end">
            <div class="mt-2">
                <div class="row">
                    <div class="col-md-10">
                        <span class="h2 mt-1">
                            <a href="{{ route('dashboard.assignments.index') }}" class="btn btn-outline-primary">
                                <span class="mx-2">
                                    Return
                                </span>
                            </a>
                            <b class="text-capitalize ms-2">Assignment {{ $assignment->user?->name }}</b>
                        </span>
                        <br>
                    </div>

                    <div class="col-md-2">
                        <div class="text-end">
                            <div class="dropdown">
                                <button class="btn btn-options" type="button" id="dropdownMenuButton"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class='bx bx-dots-horizontal-rounded'></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li><a class="dropdown-item"
                                            href="{{ route('dashboard.assignments.edit', $assignment->id) }}"><i
                                                class='bx bx-edit'></i> Edit</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <form action="{{ route('dashboard.assignments.destroy', $assignment->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="dropdown-item text-danger"><i class='bx bx-trash'></i>
                                                Remove</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="row">
                                <p class="h3 mb-4">
                                    <b><i class='bx bx-info-circle'></i> Details @if ($assignment->return_date)
                                            <span class="fs-6 text-muted">({{ $assignment->return_date }})</span>
                                        @endif
                                    </b>
                                </p>

                                <div class="fs-5">
                                    <p><b>User:</b><span class="ms-2 text-capitalize">{{ $assignment->user?->name }}</span>
                                    </p>
                                    <p><b>Item:</b> <a href="{{route('dashboard.inventories.show', $assignment->inventory->id)}}" class="ms-2">{{ $assignment->inventory?->name }} -
                                            {{ $assignment->inventory?->reference }}</a></p>
                                    <p><b>Quantity:</b> <span class="ms-2">{{ $assignment->quantity }}</span></p>
                                    <p><b>Extract date:</b> <span
                                            class="ms-2">{{ $assignment->extract_date ? $assignment->extract_date : '-' }}</span>
                                    </p>
                                    <p><b>Shop place:</b> <span
                                            class="ms-2">{{ $assignment->return_date ? $assignment->return_date : '-' }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-1">
                                <div class="row">

                                    <div class="col-12 mt-3">
                                        <div class="card bg-transparent">
                                            <div class="card-header p-4">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <div>
                                                            <h4 class="mb-0"><b><i class='bx bx-clipboard'></i> Items
                                                                    assigned</b></h4>
                                                            <div class="d-flex align-items-center">
                                                                <p class="text-muted mb-0 d-flex">Assigned to&nbsp;&nbsp;<a
                                                                        href="{{ route('dashboard.inventories.show', $assignment->user?->id) }}"
                                                                        class="text-decoration-none text-capitalize">
                                                                        <div tabindex="0" data-bs-toggle="popover"
                                                                            data-bs-trigger="hover focus"
                                                                            data-bs-content="{{ $assignment->user?->name }}"
                                                                            class="avatar avatar-s d-inline-block"><img
                                                                                src="{{ $assignment->user->profile_img }}"
                                                                                alt="user_image" class="rounded-circle">
                                                                        </div>
                                                                    </a></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <a class="card d-flex flex-row align-items-center bg-transparent border text-dark text-decoration-none w-100" href="{{route('dashboard.inventories.show', $assignment->inventory->id)}}">
                                                    <img class="m-4 p-2 shadow img-thumbnail" style="max-width: 150px" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAL4AAACpCAMAAACieXeMAAAAV1BMVEX///82Ih1GMiQ1KjYuEyNMQ0w7IyQrEx1XQiZVQ1VXQiw7LDwmExslExZdNTxVNS09IixfYWBFQktGLytbQ1tTNSZaNTVNNCw1KjtWNTNEGzRFMh1NRFTpU4KxAAAEJ0lEQVR4nO3dC3uaMBSA4aKlgENpozKp+/+/c4TrCeQgAXI5mG/duttjXyIERMY+Pux2Uc+yWEhd/2WbDPN8m3m+zb4nIs7v+hKyTYYpwT1/45zlF015+dF3ufCPvNsQF/B/QZr5uSSu7hZAnf9rhp8XfSM+X4CcPxHu8vNcquf8vPaXqa03RvlFPhp+/rNLN/xyPuY2P/rlt3ww/kWB8l+4zfLnYU6gv6OuPDv8QJl/hfCa3gT/1ifMJf5o3GnxS6dxftZVFPx7V1ClyL/a43dLQZVfwCVYtPIga49Zfibw6ycBWRqn+EXR/yKA0Rj9AixAMMuP8g3N+5Ktdsyfv+7Lh18fP8A6xfWXqz4dQCcUM5kNfly2Eb8tWk2exY9bfjzFj9zlg+F3iS/sZKf5Lo6+Ar/143xlvzl+/JqvPPgbH6V5vsP8k3t8YCTODwIWhqws5J8g8pCWRVGUNp/l4Zg/MPD7608uzOen2/PXJ+Mzqnz2io/aDfLRyWaaP2nnucMPV/NvIHP80Hk+vqeq+GGdMn8wi1vgh6D35t+08VFx+MPD+a/8TvCbGAN/oP5FDO2pdsBvdlQU+Yx1+1mRLzwttvkTU323n2VO8+9TfDZa9xfwNR8n3HlDPrbpusdvFmDezNMfPrjD5wuA72jd55frDjr6jA34zBm+KAawH5EcMviC0WV+PeMg/CVzpTBZHleTMT6DDfkrpnrPfx++fNNV5CdVRvkhnFRW8m+3pE2cbWjwk56fJJD/0MkPtxv9m5yvcd03wd9Uvye+cA4hVCcL/IQ0/8HTQ4Z5vjTPR2peGt5HLxDX8sGOKtHJ7/0ZSX752tDE6D+08Y2sPJ4/jp+Ryujyobi6KKHtDFvwwMT5R9CmYqHXfP5+z4IH3g3/4flYW/Lh1pp0+DKy/HrOIcs/WuefPd8aPyXEh4c5zVpfN++x2snG1swD+SlMja95Twvz/C7PV20lX3qc4PmeT5p/9nzDfOGYZxbfglhor/x5m67nK9edUc4G13wR4d/v0tOaM/mWJ5t69GVX3BHhN6tORppfrUOE+dnUpovP+5b5/ZZLko+KhXM7+Dl9V/dUnm+gOfyz52tqAR99VeIq/+z5nu/5LvMPz+fhUP9QvSUh51s4tEEb8p+0+cIbQtT4g/ezdsP/126qNPm2XxO+NR+d6l3j9zMnPX5Lp85/0uU/x/zUJbHQrvjPmj+YeVIifMnEWV/CQ5XfnBckyE/Bic298PVeDqXeBL8riujyI9L8SOQfXeZXZxiGo8/r+Vr/qZh6Y34bvDHTkqvGjYTwT3EM7yFFid/cw9Tz9Yfwq3sMU+U3t0gG2WZiDfjCHZ6p8Qc3qHZf//HV48Wb+RPh9//DBUW+EBQT5xMhwzzfZvvgf0aj28SSaCf8be4Ub7xtb3RvPM8323+1b4DCHcUNLQAAAABJRU5ErkJggg==" alt="...">
                                                    <div class="card-body">
                                                      <h5 class="card-title">{{$assignment->inventory->name}} <span class="text-muted fs-6">({{$assignment->inventory->reference}})</span></h5>
                                                      <p class="card-text">{{$assignment->inventory->description?$assignment->inventory->description:"-"}}</p>
                                                    </div>
                                                  </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
        const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))
    </script>
@endsection
