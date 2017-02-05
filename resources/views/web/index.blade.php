<!doctype html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="_token" content="{{csrf_token()}}">
    <title>{{env('APP_NAME', 'E-shopper')}}</title>
    <link rel="stylesheet" href="{{elixir('css/app.css')}}">
    <link rel="stylesheet" href="{{elixir('css/web.css')}}">
</head>
<body>
    <div id="app">{{env('APP_NAME', 'E-shopper')}}</div>
    <script src="{{elixir('js/web.js')}}"></script>
</body>
</html>
