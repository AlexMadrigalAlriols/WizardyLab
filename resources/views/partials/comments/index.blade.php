<div class="row p-3 border-bottom comment-card">
    <div class="col-md-1">
        <img src="{{ $comment->user->profile_img }}" class="rounded-circle" width="45" height="45" alt="Profile">
    </div>
    <div class="col-md-10">
        <p class="h6">{{ $comment->user->name }}</p>
        <p class="text-muted">{{ $comment->text }}</p>
    </div>
    <div class="col-md-1">
        <div class="dropdown">
            <button class="btn btn-options" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
                aria-expanded="false">
                <i class='bx bx-dots-horizontal-rounded'></i>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <li>
                    <form action="{{route('dashboard.comments.destroy', [$task->id, $comment->id])}}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="dropdown-item text-danger" type="button" onclick="deleteComment(this)"><i class='bx bx-trash' ></i> Remove</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
