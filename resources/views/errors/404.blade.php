<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>{{ trans('global.site_title') }} | 404</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" crossorigin="anonymous">
  <meta name="robots" content="noindex, follow">
  <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
</head>

<body>
  <div class="mt-5">
    <div class="container text-center">
        <img src="{{ asset('img/errors/404.png') }}" alt="404" class="img-fluid">
        <h2 class="mt-3"><b>Sorry ! The Cool Kid Are Here</b></h2>
        <p>It looks like you're lost. It's time to go back to the main page.</p>
    </div>
  </div>
</body>
</html>
