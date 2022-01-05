<!DOCTYPE html>
<html lang="zh-TW">
    <head>
        <title>會員中心</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('/css/app.css') }}">
        @yield('link')
        <script src="{{ asset('/js/app.js') }}"></script>
    </head>
    <body>
        @yield('content')
    </body>
</html>
