@csrf
<div class="row">
    <div class="col-md-10">
        <div class="form-floating mt-3">
            <input type="text" class="form-control" id="name" name="name" maxlength="20" placeholder="Name" value="{{ old('name') ?? $item->name }}">
            <label for="name">{{ __('crud.items.fields.name') }} <span class="text-danger">*</span></label>
        </div>
        <div class="mt-0 text-end">
            <span class="text-muted"><span id="nameCountChar">0</span>/20</span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-7">
        <div class="form-floating mt-3">
            <input type="text" class="form-control" id="reference" name="reference" maxlength="30" placeholder="Reference" value="{{ old('reference') ?? $item->reference }}">
            <label for="reference">{{ __('crud.items.fields.reference') }} <span class="text-danger">*</span></label>
        </div>
        <div class="mt-0 text-end">
            <span class="text-muted"><span id="referenceCountChar">0</span>/30</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="row">
            <div class="col-9">
                <div class="form-floating mt-3">
                    <input type="number" class="form-control" id="stock" name="stock" placeholder="Stock" value="{{ old('stock') ?? $item->stock }}">
                    <label for="stock">{{ __('crud.items.fields.stock') }} <span class="text-danger">*</span></label>
                </div>
            </div>

            <div class="col-3">
                <div class="mt-4">
                    <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addStockMovementModal"><i class='bx bx-plus-medical' ></i></button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-5">
        <div class="form-floating mt-3">
            <input type="number" step="any" class="form-control" id="price" name="price" placeholder="Price" value="{{ old('price') ?? $item->price }}">
            <label for="reference">{{ __('crud.items.fields.price') }}</label>
        </div>

    </div>
    <div class="col-md-5">
        <div class="form-floating mt-3">
            <input type="text" class="form-control" id="shop_place" name="shop_place" placeholder="Shop place" value="{{ old('shop_place') ?? $item->shop_place }}">
            <label for="stock">{{ __('crud.items.fields.shop_place') }}</label>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-10">
        <div class="form-floating mt-3">
            <textarea class="form-control textricheditor" placeholder="Item Description" id="description" name="description" style="height: 85px;">{{old('description') ?? $item->description}}</textarea>
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-10">
        <span class="h3">{{ __('crud.items.fields.files') }}</span>
        <div class="dropzone mt-2" id="inventoryFileDropZone"></div>
    </div>
</div>
