@extends('layouts.dashboard', ['section' => 'Assignments'])

@section('content')

<div class="row">
    <div class="col-md-8 col-sm-12">
        <div class="mt-1">
            <span class="h2 d-inline-block mt-1">
                <b>Create an Assignment</b>
            </span>
        </div>
        <form action="{{route('dashboard.assignments.store')}}" method="POST" class="mt-2 pb-3">
            @csrf
            @include('partials.assignments.form')
            <div class="row mt-4">
                <div class="col-md-10" style="display:flex; justify-content:right; align-items:center;">
                    <a class="btn btn-outline-primary" href="{{route('dashboard.assignments.index')}}"><span class="px-2">Cancel</span></a>
                    <button class="btn btn-primary ms-2"><span class="px-5">Create assignment</span></button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
    $('#inventory_id').change(function() {
        let stock = $(this).find(':selected').data('stock');
        $('#quantity').attr('max', stock);

        $('#qtyCounter').removeClass('d-none').text(stock);
    });

    $('#inventory_id').trigger('change');
</script>
@endsection
