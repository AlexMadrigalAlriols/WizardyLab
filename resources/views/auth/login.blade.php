@extends('layouts.app', ['section' => 'Login'])
@section('content')
    <img class="wave" src="{{ asset('img/wave.png') }}">
    <div class="row h-100">
        <div class="col-md-8 col-lg-9 img-container d-flex align-items-center justify-content-center">
            <img src="{{ asset('img/bg_login.svg') }}">
        </div>
        <div class="col-md-4 col-lg-3 col-sm-12 login-container d-flex justify-content-center">
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <img src="{{ asset('img/LogoLetters.png') }}" class="mt-5 mb-5">
                <h2 class="title mt-5">Welcome</h2>
                <div class="input-div {{ $errors->has('email') ? 'error' : '' }}">
                    <div class="icon">
                        <i class="bx bx-envelope"></i>
                    </div>
                    <div class="input-container">
                        <h5>Email</h5>
                        <input type="text" name="email" class="login-input">

                    </div>
                </div>
                @if ($errors->has('email'))
                    <p class="text-danger text-start mt-1 mb-0"><i class='bx bx-error-alt' ></i> {{ $errors->first('email') }}</p>
                @endif
                <div class="input-div mt-4 {{ $errors->has('password') ? 'error' : '' }}">
                    <div class="icon">
                        <i class="bx bx-lock-alt"></i>
                    </div>
                    <div class="input-container">
                        <h5>Password</h5>
                        <input type="password" name="password" class="login-input">
                    </div>
                </div>
                @if ($errors->has('password'))
                    <p class="text-danger text-start mt-2 mb-0"><i class='bx bx-error-alt' ></i> {{ $errors->first('password') }}</p>
                @endif
                <div class="rememberme mt-3 text-start">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Remember me</label>
                </div>
                <a href="#">{{trans('global.auth.forgot_password')}}?</a>
                <button type="submit" class="btn mt-4"><i class='bx bx-log-in'></i> {{ trans('global.auth.login_button') }}</button>
            </form>
        </div>
    </div>
@endsection
{{-- @section('content')
    <img class="wave" src="{{ asset('img/wave.png') }}">
    <div class="container">
        <div class="img">
            <img src="{{ asset('img/bg_login.svg') }}">
        </div>
        <div class="login-content">
            <form action="{{ route('login') }}">
                <img src="{{ asset('img/avatar.svg') }}">
                <h2 class="title">Welcome</h2>
                <div class="input-div one">
                    <div class="i">
                        <i class="bx bx-envelope"></i>
                    </div>
                    <div class="div">
                        <h5>Email</h5>
                        <input type="text" name="email" class="input">
                    </div>
                </div>
                <div class="input-div pass">
                    <div class="i">
                        <i class="bx bx-lock-alt"></i>
                    </div>
                    <div class="div">
                        <h5>Password</h5>
                        <input type="password" name="password" class="input">
                    </div>
                </div>
                <a href="#">Forgot Password?</a>
                <button type="submit" class="btn">Login</button>
            </form>
        </div>
    </div>
@endsection --}}

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

            $('#email, #password').keyup(function() {
                if ($('#email').val() != '' && $('#password').val() != '') {
                    $('#login-button').removeAttr('disabled');
                } else {
                    $('#login-button').prop('disabled', true);
                }
            });
        });
    </script>
@endsection
