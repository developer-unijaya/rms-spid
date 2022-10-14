<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> {{ env('APP_NAME', '') }} - SSO Auth Failed </title>
</head>
<body style="background-color: #898AA6">

    <center>
        <br/><br/><br/>

        <h2> SSO Auth Failed </h2>
        <p> {{ $failed_msg }} </p>

        <br/><br/><br/>

        <a href="{{ url('/') }}"> {{ env('APP_NAME', '') }} HOME </a>
    </center>

</body>
</html>
