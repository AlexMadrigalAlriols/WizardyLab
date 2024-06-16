@csrf
<div class="row">
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <input type="text" class="form-control @if ($errors->has('name')) is-invalid @endif" maxlength="60"
                id="name" name="name" placeholder="Name" value="{{ old('name') ?? $user->name }}">
            <label for="title">{{ __('crud.users.fields.name') }} <span class="text-danger">*</span></label>
        </div>

        <div class="mt-0 text-end">
            <span class="text-muted"><span id="nameCountChar">0</span>/60</span>
        </div>

        @if ($errors->has('name'))
            <span class="text-danger">{{ $errors->first('name') }}</span>
        @endif
    </div>
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <input type="date" class="form-control" id="birthday_date" name="birthday_date"
                placeholder="Birthday Date" value="{{ old('birthday_date') ?? $user->birthday_date?->format('Y-m-d') }}">
            <label for="floatingSelect">Birthday <span class="text-danger">*</span></label>
        </div>

        @if ($errors->has('birthday_date'))
            <span class="text-danger">{{ $errors->first('birthday_date') }}</span>
        @endif
    </div>
</div>
    <div class="row">
        <div class="col-md-8">
            <div class="form-floating mt-3">
                <input type="email" class="form-control @if ($errors->has('email')) is-invalid @endif"
                    maxlength="40" id="email" name="email" placeholder="Email"
                    value="{{ old('email') ?? $user->email }}">
                <label for="email">{{ __('crud.users.fields.email') }} <span class="text-danger">*</span></label>
            </div>
            <div class="mt-0 text-end">
                <span class="text-muted"><span id="emailCountChar">0</span>/40</span>
            </div>

            @if ($errors->has('email'))
                <span class="text-danger">{{ $errors->first('email') }}</span>
            @endif
        </div>
        <div class="col-md-4">
            <div class="form-floating mt-3">
                <select class="form-select select2 @if ($errors->has('gender')) is-invalid @endif" id="gender"
                    name="gender" aria-label="Gender">
                    @foreach ($user::GENDERS as $gender)
                        <option value="{{ $gender }}"
                            {{ $user->gender == $gender || old('gender') == $gender ? 'selected' : '' }}>
                            {{ $gender }}</option>
                    @endforeach
                </select>
                <label for="phone">{{ __('crud.users.fields.gender') }}</label>
            </div>

            @if ($errors->has('gender'))
                <span class="text-danger">{{ $errors->first('gender') }}</span>
            @endif
        </div>
    </div>
<div class="row">
        <div class="col-md-6">
            <div class="form-floating mt-3">
                <select class="form-select select2 @if ($errors->has('department_id')) is-invalid @endif" id="department_id"
                    name="department_id" aria-label="Department">
                    @foreach ($departments as $department)
                        <option value="{{$department->id}}"
                            {{ $user->department_id == $department->id || old('department_id') == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}</option>
                    @endforeach
                </select>
                <label for="floatingSelect">{{ __('crud.users.fields.department') }}</label>
            </div>

            @if ($errors->has('department_id'))
                <span class="text-danger">{{ $errors->first('department_id') }}</span>
            @endif
        </div>
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <select class="form-select select2" id="country_id" name="country_id" aria-label="Country">
                @foreach ($countries as $country)
                    <option value="{{ $country->id }}"
                        {{ $user->country_id == $country->id || old('country_id') == $country->id ? 'selected' : '' }}>
                        {{ $country->name }}</option>
                @endforeach
            </select>
            <label for="floatingSelect">{{ __('crud.users.fields.country') }}</label>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <select class="form-select select2" id="role_id" name="role_id" aria-label="role_id">
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}"
                        {{ $user->role_id == $role->id || old('role_id') == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}</option>
                @endforeach
            </select>
            <label for="floatingSelect">{{ __('crud.users.fields.role')}}</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <select class="form-select select2" id="reporting_user_id" name="reporting_user_id" aria-label="reporting_user_id">
                <option value="">
                    --</option>
                @foreach ($qusers as $quser)
                    @if ($quser != $user && $quser->reportinguser != $user)
                        <option value="{{ $quser->id }}"
                        {{ $user->reporting_user_id == $quser->id || old('reporting_user_id') == $quser->id ? 'selected' : '' }}>
                        {{ $quser->name }}</option>
                    @endif
                @endforeach
            </select>
            <label for="floatingSelect">{{ __('crud.users.fields.report_to') }}</label>
        </div>
 </div>
</div>


<h4 class="mt-4 credentials"><i class='bx bx-shield'></i>Credentials</h4>
<div class="row" noEdit>
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <input type="password" class="form-control" id="password" name="password" placeholder="password" value="{{ old('password') }}">
            <label for="floatingSelect">initial password <span class="text-danger">*</span></label>
        </div>
    </div>
</div>
@if (session('status'))
    <div class="alert alert-success" role="alert">
        {{session("status")}}
    </div>
 @endif



<div class="row" siEdit>
    <div class="col-md-6">
        <div class="form-floating mt-3">
            <a href="{{ route('sendResetLink', ['email' => $user->email])}}" class="btn btn-danger">Send mail reset passowrd</a>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-12">
        <span class="h3">Upload Files</span>
        <div class="dropzone mt-2" id="taskDropZone"></div>
    </div>
</div>
