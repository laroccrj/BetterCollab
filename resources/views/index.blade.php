<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>AUX Control</title>
    <link href=" {{ mix('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ mix('css/reset.css') }}" />
    <style>
        html, body, #app {
            height: 100vh;
            margin: 0;
        }
    </style>
</head>
<body>
<div id="app">
    <app></app>
</div>
<script src="{{ mix('js/bootstrap.js') }}"></script>
<script src="{{ mix('js/app.js') }}"></script>
<script src="https://kit.fontawesome.com/31c8262794.js" crossorigin="anonymous"></script>
</body>
</html>
