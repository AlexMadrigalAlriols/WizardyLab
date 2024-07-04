<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body style="display:flex; flex-direction: column; justify-content:center; align-items:center;">
    <h1>New lead from landing WizardyLab</h1>
    <b>Name: </b>{{$data["name"]}}
    <b>Mail: </b>{{$data["email"]}}
    <b>Phone Number: </b>{{$data["phone_number"]}}
    <b>Text: </b>{{$data["message"]}}
</body>
</html>
