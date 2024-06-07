@csrf
<div class="row">
    <div class="col-md-10">
        <div class="form-floating mt-3">
            <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="{{ old('name') ?? $inventory->name }}">
            <label for="name">Name <span class="text-danger">*</span></label>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-7">
        <div class="form-floating mt-3">
            <input type="text" class="form-control" id="reference" name="reference" placeholder="Reference" value="{{ old('reference') ?? $inventory->reference }}">
            <label for="reference">Reference <span class="text-danger">*</span></label>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-floating mt-3">
            <input type="number" class="form-control" id="stock" name="stock" placeholder="Stock" value="{{ old('stock') ?? $inventory->stock }}">
            <label for="stock">Stock <span class="text-danger">*</span></label>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-5">
        <div class="form-floating mt-3">
            <input type="number" step="any" class="form-control" id="price" name="price" placeholder="Price" value="{{ old('price') ?? $inventory->price }}">
            <label for="reference">Price $</label>
        </div>

    </div>
    <div class="col-md-5">
        <div class="form-floating mt-3">
            <input type="text" class="form-control" id="shop_place" name="shop_place" placeholder="Shop place" value="{{ old('shop_place') ?? $inventory->shop_place }}">
            <label for="stock">Shop place</label>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-10">
        <div class="form-floating mt-3">
            <textarea class="form-control textricheditor" placeholder="Item Description" id="description" name="description" style="height: 85px;">{{old('description') ?? $inventory->description}}</textarea>
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-10">
        <span class="h3">Upload Files</span>
        <div class="dropzone mt-2" id="inventoryFileDropZone"></div>
    </div>
</div>
