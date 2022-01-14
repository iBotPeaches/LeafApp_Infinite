<!doctype html>
<html class="has-navbar-fixed-top" lang="en">
<head>
    <title>@yield('title')</title>
    <meta name="description" content="@yield('description')" />
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <livewire:styles />
</head>

<body class="is-flex is-flex-direction-column">
    @include('partials.global.navigation')
    <main class="container mt-4">
        @yield('content')
    </main>
    @include('partials.global.footer')
    <script src="{{ mix('js/app.js') }}"></script>
    <livewire:scripts />
</body>
</html>
