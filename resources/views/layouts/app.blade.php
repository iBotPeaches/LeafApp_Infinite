<!doctype html>
<html class="has-navbar-fixed-top" lang="en">
<head>
    <title>@yield('title')</title>
    <meta name="description" content="@yield('description')" />
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    {!! Artesaos\SEOTools\Facades\SEOTools::generate() !!}
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <livewire:styles />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
</head>

<body class="is-flex is-flex-direction-column">
    @include('partials.global.navigation')
    <main class="container mt-4 mb-1">
        @yield('content')
    </main>
    @include('partials.global.footer')
    <livewire:scripts />
</body>
</html>
