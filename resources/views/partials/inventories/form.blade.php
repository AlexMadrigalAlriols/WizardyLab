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
            <textarea class="form-control" placeholder="Item Description" id="description" name="description" style="height: 85px;">{{old('description') ?? $inventory->description}}</textarea>
            <label for="description">Description</label>
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-10">
        <span class="h3">Upload Files</span>
        <div class="dropzone mt-2" id="logoDropzone"></div>
    </div>
</div>

@section('scripts')
    <script>
        Dropzone.autoDiscover = false;

        $(document).ready(function() {
            new Dropzone("#logoDropzone", {
                url: "{{ route('dashboard.task.upload_file') }}", // Ruta donde manejarás la carga de archivos
                paramName: "dropzone_image", // Nombre del campo de formulario para el archivo
                maxFilesize: 2, // Tamaño máximo en MB
                acceptedFiles: ".jpeg,.jpg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.txt",
                addRemoveLinks: true,
                uploadMultiple: true,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            });
        });

    </script>

@endsection
