<div class="dropdown">
    <button class="btn btn-options" type="button" id="dropdownActions" data-bs-toggle="dropdown"
        aria-expanded="false">
        <i class='bx bx-dots-horizontal-rounded'></i>
    </button>
    <ul class="dropdown-menu" aria-labelledby="dropdownActionsButton">
        @if (isset($viewGate) && $viewGate)
            <li><a class="dropdown-item" href="{{route('dashboard.'.$crudRoutePart.'.show', $row->id)}}"><i class='bx bx-show' ></i> Edit</a></li>
        @endif
        @if (isset($editGate) && $editGate)
            <li><a class="dropdown-item" href="{{route('dashboard.'.$crudRoutePart.'.edit', $row->id)}}"><i class='bx bx-edit' ></i> Edit</a></li>
        @endif
        @if (isset($deleteGate) && $deleteGate)
            <li><hr class="dropdown-divider"></li>
            <li>
                <form action="{{route('dashboard.'.$crudRoutePart.'.destroy', $row->id)}}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="dropdown-item text-danger"><i class='bx bx-trash' ></i> Remove</button>
                </form>
            </li>
        @endif
    </ul>
</div>
