<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> {{ env('APP_NAME', '') }} - SSO Auth Failed </title>
</head>
<body style="background-color: #B4B5B5">

    <center>
        <br/><br/><br/>

        <h1> SSO Auth Failed </h1>
        <p> {{ $failed_msg }} </p>

        <br/><br/><br/>

        <a href="{{ url('/') }}"> {{ env('APP_NAME', '') }} HOME </a>
    </center>

</body>
</html>
