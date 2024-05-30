<div class="d-flex gap-2 pb-2 mb-3 border-bottom">
    <i class="{{$activity->icon}} border rounded-pill p-1 text-primary" style="width: 25px; height: 25px;"></i>
    <div class="activity-item">
        <p class="mb-1 fs-9" style="font-size: 12px;"><span class="fw-bold"> {{$activity->user->name}} </span> {!! $activity->data['message'] !!}</p>
        <div class="d-flex gap-2 justify-content-between fs-9" style="font-size: 12px;">
            <p class="mb-0"><i class="bx bx-time-five"></i>{{$activity->created_at->format('h:i A')}}</p>
            <p class="mb-0">{{$activity->created_at->format('F j, Y')}}</p>
        </div>
    </div>
</div>
