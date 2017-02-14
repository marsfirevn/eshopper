<!doctype html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="_token" content="{{csrf_token()}}">
    <title>{{env('APP_NAME', 'E-shopper')}}</title>
    <link rel="stylesheet"
          href="http://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,500&amp;subset=vietnamese">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500&amp;subset=vietnamese">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{elixir('css/app.css')}}">
    <link rel="stylesheet" href="{{elixir('css/web.css')}}">
</head>
<body>
<div id="app">{{env('APP_NAME', 'E-shopper')}}</div>
<script src="{{elixir('js/web.js')}}"></script>
</body>
</html>
