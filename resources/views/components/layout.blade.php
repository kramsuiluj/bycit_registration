<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="{{ asset('images/JPCS.png') }}" type="image/png">
    <title>BYCIT Registration</title>
    <style>
        body {
            background-image: url("{{ asset('images/bg2.jpg') }}");
            background-repeat: no-repeat;
            background-size: cover;
            background-attachment: fixed;
            /* background-image: url({{ asset('images/background.png') }});-- */
            /*background-repeat: repeat-y;*/
            /*position: fixed;*/
            /* overflow-y: scroll;
            background: rgb(22,17,119);
            background: linear-gradient(34deg, rgba(22,17,119,1) 0%, rgba(51,51,188,1) 35%, rgba(255,0,235,1) 100%); */
            /*background-repeat: no-repeat;*/
            /* background-repeat: no-repeat;
            background-attachment: fixed;*/
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

        input[type="text"]{
            text-transform: capitalize;
        }

        .center {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }
    </style>

    <style>
        #others-container {}

        #others-container>div {
            margin-bottom: 1.25rem;
        }

        #others-container>div:last-child {
            margin-bottom: 0;
        }

        @media (min-width: 481px) {
            #others-container {
                display: flex;
            }

            #others-container>div {
                margin-right: 0.50rem;
                margin-bottom: 0;
            }

            #others-container>div:last-child {
                margin-right: 0;
            }

            body {
                background-image: url("{{ asset('images/bg3.jpg') }}");
            }

        }

        @media (min-width: 1025px) {
            body {
                background-image: url("{{ asset('images/bg2.jpg') }}");
            }
        }
    </style>
</head>

<body class="h-screen">
    {{ $slot }}
</body>

</html>
