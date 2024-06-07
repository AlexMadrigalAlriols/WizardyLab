@csrf
<div class="row">
    <div class="col-md-10">
        <div class="form-floating mt-3">
            <select class="form-select select2" id="user_id" name="user_id">
              @foreach ($users as $user)
                <option value="{{$user->id}}" {{($assignment->user_id == $user->id || old('project') == $user->id) ? 'selected' : ''}}>{{ $user->name }}</option>
              @endforeach
            </select>
            <label for="floatingSelect">User <span class="text-danger">*</span></label>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-7">
        <div class="form-floating mt-3">
            <select class="form-select select2" id="inventory_id" name="inventory_id">
              @foreach ($inventories as $item)
                <option value="{{$item->id}}" data-stock="{{ $item->remaining_stock }}" {{($assignment->inventory_id == $item->id || old('project') == $item->id) ? 'selected' : ''}}>{{ $item->name }} - {{ $item->reference }}</option>
              @endforeach
            </select>
            <label for="floatingSelect">Item <span class="text-danger">*</span></label>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-floating mt-3">
            <input type="number" class="form-control" id="quantity" name="quantity" min="1" placeholder="Quantity" value="{{ (old('quantity') ?? $assignment->quantity) ?? 1 }}">
            <label for="stock">Quantity <span class="text-danger">*</span></label>
        </div>

        <div class="text-end text-muted">
            Max. <span class="d-none" id="qtyCounter">1</span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-5">
        <div class="form-floating mt-3">
            <input type="date" class="form-control" id="extract_date" name="extract_date" placeholder="Extract date" value="{{ old('extract_date') ?? $assignment->extract_date }}">
            <label for="extract_date">Extract date</label>
        </div>

    </div>
    <div class="col-md-5">
        <div class="form-floating mt-3">
            <input type="date" class="form-control" id="return_date" name="return_date" placeholder="Return date" value="{{ old('return_date') ?? $assignment->return_date }}">
            <label for="return_date">Return date</label>
        </div>
    </div>
</div>


