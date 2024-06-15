<!-- resources/views/auth/reset.blade.php -->

{{-- <form method="POST" action="/password/reset">
    {!! csrf_field() !!}
    <input type="hidden" name="token" value="{{ $token }}">

    @if (count($errors) > 0)
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <div>
        Email
        <input type="email" name="email" value="{{ old('email') }}">
    </div>

    <div>
        Password
        <input type="password" name="password">
    </div>

    <div>
        Confirm Password
        <input type="password" name="password_confirmation">
    </div>

    <div>
        <button type="submit">
            Reset Password
        </button>
    </div>
</form> --}}
@extends('layouts.app', ['section' => 'Login'])
@section('content')
    <img class="wave" src="{{ asset('img/wave.png') }}">
    <div class="row h-100">
        <div class="col-md-8 col-lg-9 img-container d-flex align-items-center justify-content-center">
            <img src="{{ asset('img/forgotPassword.svg') }}">
        </div>
        <div class="col-md-4 col-lg-3 col-sm-12 login-container d-flex justify-content-center">
            <form action="/password/reset" method="POST">
                {!! csrf_field() !!}
                <input type="hidden" name="token" value="{{ $token }}">
            <img src="{{ asset('img/LogoLetters.png') }}" class="mt-5 mb-5">
            <h2 class="title mt-5">Reset password</h2>

            @if (count($errors) > 0)
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <div class="input-div">
                <div class="icon">
                    <i class="bx bx-envelope"></i>
                </div>
                <div class="input-container">
                    <h5>Email</h5>
                    <input type="email" name="email" value="{{ old('email') }}" class="login-input">
                </div>
            </div>

            <div class="input-div mt-4">
                <div class="icon">
                    <i class="bx bx-lock-alt"></i>
                </div>
                <div class="input-container">
                    <h5>Password</h5>
                    <input type="password" name="password" class="login-input">
                </div>
            </div>


            <div class="input-div mt-4">
                <div class="icon">
                    <i class="bx bx-lock-alt"></i>
                </div>
                <div class="input-container">
                    <h5>Confirm Password</h5>
                    <input type="password" name="password_confirmation" class="login-input">
                </div>
            </div>
                <button type="submit" class="btn mt-4"><i class='bx bx-log-in'></i> Reset password</button>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            const inputs = document.querySelectorAll(".login-input");

            function addcl() {
                let parent = this.parentNode.parentNode;
                parent.classList.add("focus");
            }

            function remcl() {
                let parent = this.parentNode.parentNode;
                if (this.value == "") {
                    parent.classList.remove("focus");
                }
            }


            inputs.forEach(input => {
                input.addEventListener("focus", addcl);
                input.addEventListener("blur", remcl);
            })
        });
    </script>
@endsection
