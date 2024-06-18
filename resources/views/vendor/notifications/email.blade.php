<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<style></style>
<body class="bg-light" style="background-size:cover; background-repeat:no-repeat; background-image:url('{{ asset('img/wave.png') }}')">
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8 d-flex justify-content-center align-items-center flex-column">
                <div class=" d-flex align-items-center justify-content-center">
                    <img style="max-width:400px;" src="{{ asset('img/LogoLetters.png') }}" class="mt-5 mb-5">
                </div>
                <div class="card">
                    <div class="card-body">
                        <h1 class="card-title">Password reset</h1>
                        <p>Hi, {{ $user->name }}👋</p>
                        <p>If you receive this email it is because a superior has activated your password recovery.</p>
                        <p>Click the following button to reset your password:</p>
                        <div class="d-flex justify-content-center align-items-center my-4">
                            <a href="{{ $actionUrl }}" class="btn btn-primary" style="font-size: 20px"> {{ $actionText }}</a>
                        </div>
                        <div class="text-muted" style="font-size: 13px">
                            <p>If you did not request a password reset, no further action is required.</p>
                            <p>Thanks,</p>
                            <p>{{ config('app.name') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
