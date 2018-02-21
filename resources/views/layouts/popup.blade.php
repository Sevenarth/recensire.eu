<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-white">
    <div id="app">
      <div class="p-3">
        <img src="/images/arts.svg" class="image-responsive" height="30" alt="{{ config('app.name', 'Laravel') }}">
      </div>

      <div class="px-3 pb-3">
            @yield('content')
      </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/upload.js') }}"></script>
</body>
</html>
