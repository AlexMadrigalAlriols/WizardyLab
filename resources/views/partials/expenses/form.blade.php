@csrf
<div class="row">
    <div class="col-md-8" id="project">
        <div class="form-floating mt-3">
            <select class="form-select @if($errors->has('project_id')) is-invalid @endif" id="project_id" name="project_id" aria-label="Project">
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}" {{$expense->project_id == $project->id || old('project_id') == $project->id ? 'selected' : ''}}>{{ $project->name }}</option>
                @endforeach
            </select>
            <label for="floatingSelect">Project <span class="text-danger">*</span></label>
        </div>

        @if ($errors->has('project_id'))
            <span class="text-danger">{{ $errors->first('project_id') }}</span>
        @endif
    </div>

    <div class="offset-md-1 col-md-3 align-middle">
        <div class="form-check form-switch mt-4">
            <input class="form-check-input" type="checkbox" name="facturable" role="switch" checked id="facturableCheck">
            <label class="form-check-label align-middle ms-3" for="facturableCheck">Facturable</label>
          </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <expenses-form :inventory-items="{{json_encode($inventoryArray)}}" :assignments="{{json_encode([])}}"></expenses-form>

        @if ($errors->has('items'))
            <span class="text-danger">{{ $errors->first('items') }}</span>
        @endif
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-12">
        <span class="h3">Bills</span>
        <div class="dropzone mt-2" id="billFileDropZone"></div>
    </div>
</div>
