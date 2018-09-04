<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="referrer" content="no-referrer">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="og:image" content="{{ asset('images/logo.png') }}">
    @yield('meta')

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-white">
    <div id="app">
      <div class="loading"></div>
      @section('header')
        <nav class="my-2 navbar navbar-expand-md navbar-light">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="/images/logo.svg" height="50" class="image-responsive logo" alt="{{ config('app.name', 'Laravel') }}">
                </a>
                @yield('navbar')
            </div>
        </nav>
        @show

        <main class="pt-3">
            @yield('content')
        </main>
        @section('footer')
        <footer class="container text-muted mb-3">
            <hr>
            <small>Copyright &copy; {{ date('Y') }} Recensire.eu. All rights are reserved.</small>
        </footer>
        @show
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/public.js') }}"></script>
    <div id="js-scripts">
      @yield('scripts')
    </div>
</body>
</html>