<div class="btn-group">
    <button type="button" class="btn btn-sm dropdown-toggle" style="{{$selected_status->styles}}" data-bs-toggle="dropdown" aria-expanded="false">
      <b>{{$selected_status->title}}</b>
    </button>
    <ul class="dropdown-menu dropdown-menu-sm">
        @foreach ($statuses as $status)
            <li class="border-top"><a class="dropdown-item" href="{{route($route, [$model->id, $status->id])}}"><span class="badge" style="{{$status->styles}}">{{$status->title}}</span></a></li>
        @endforeach
    </ul>
</div>
