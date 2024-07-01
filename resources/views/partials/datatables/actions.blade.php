<div class="dropdown">
    <button class="btn btn-options" type="button" id="dropdownActions" data-bs-toggle="dropdown"
        aria-expanded="false">
        <i class='bx bx-dots-horizontal-rounded'></i>
    </button>
    <ul class="dropdown-menu" aria-labelledby="dropdownActionsButton">
        @foreach ($links ?? [] as $link)
        @if (isset($link['permission']))
            @can($link['permission'])
                <li><a class="dropdown-item" href="{{ $link['href'] }}"><i class='{{ $link['icon'] }}' ></i> {{ $link['text'] }}</a></li>
            @endcan
        @else
            <li><a class="dropdown-item" href="{{ $link['href'] }}"><i class='{{ $link['icon'] }}' ></i> {{ $link['text'] }}</a></li>
        @endif
        @endforeach
        @if (isset($viewGate))
            @can($viewGate)
                <li><a class="dropdown-item" href="{{route('dashboard.'.$crudRoutePart.'.show', $row->id)}}"><i class='bx bx-show' ></i>  {{__('global.view')}}</a></li>
            @endcan
        @endif
        @if (isset($editGate) && $editGate)
            @can($editGate)
                <li><a class="dropdown-item" href="{{route('dashboard.'.$crudRoutePart.'.edit', $row->id)}}"><i class='bx bx-edit' ></i>  {{__('global.edit')}}</a></li>
            @endcan
        @endif
        @if (isset($deleteGate) && $deleteGate)
            @can($deleteGate)
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{route('dashboard.'.$crudRoutePart.'.destroy', $row->id)}}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="dropdown-item text-danger"><i class='bx bx-trash' ></i> {{__('global.remove')}}</button>
                    </form>
                </li>
            @endcan
        @endif
    </ul>
</div>
