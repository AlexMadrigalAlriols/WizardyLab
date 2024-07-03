<div>
    <img src="{{$user->profile_url}}" alt="" class="rounded-circle d-inline-block" width="50" height="50">
    <div class="d-inline-block ms-3 align-middle mt-3">
        <b style="">{{ \Illuminate\Support\Str::limit($user->name, $limit ?? 40) }}</b>@if ($user->id == auth()->user()->id)<span class="badge bg-secondary ms-2">It's You</span>@endif
        <p class="text-muted">{{$user->role->name ?? '-'}}</p>
    </div>
</div>
