<!DOCTYPE html>
<html lang="zh-TW">
    <head>
        <title>登入</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <link rel="stylesheet" href="{{ asset('/css/app.css') }}">
        @yield('link')
        <script src="{{ asset('/js/app.js') }}"></script>
    </head>
    <body>
        @yield('content')
    </body>
</html>
