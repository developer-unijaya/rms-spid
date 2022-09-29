<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> {{ env('APP_NAME', '') }} - ssoAuthFailed </title>
</head>
<body>

    <center>
        <h4> ssoAuthFailed </h4>
        <p> {{ $failed_msg }} </p>

        <br/><br/><br/>

        <a href="{{ url('/') }}"> {{ env('APP_NAME', '') }} HOME </a>

    </center>

</body>
</html>
