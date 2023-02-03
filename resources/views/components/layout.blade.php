<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <link rel="icon" href="{{asset('images/JPCS.png')}}" type="image/png">
    <title>BYCIT Registration</title>
    <style>
        body {
            /*background: rgb(22,17,119);*/
            /*background: linear-gradient(54deg, rgba(22,17,119,1) 0%, rgba(51,51,188,1) 35%, rgba(255,0,235,1) 100%);*/
            /*background-repeat: no-repeat;*/
            background-image: url({{ asset('images/background.png') }});
            background-repeat: repeat-y;
            position: fixed;
            overflow-y: scroll;
            width: 100%;
        }

        input[type="radio"] {
            appearance: none;
            background-color: white;
            border-radius: 5px;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            -ms-border-radius: 5px;
            -o-border-radius: 5px;
        }

        input[type="radio"]:checked {
            padding: 2px;
            background-color: #3164f4;
            border: 2px solid white;
        }

        .center {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
</head>
<body class="h-screen">
    {{ $slot }}
</body>
</html>
