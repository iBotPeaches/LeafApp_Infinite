<!doctype html>
<html class="no-js" lang="">
<head>
    <title>LeafApp - @yield('title')</title>
    <meta name="description" content="LeafApp @yield('description')" />
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <livewire:styles />
</head>
<!-- prevent flash of unstyled content in FF -->
<script>0</script>

<body class="bg-grey-light font-sans leading-normal tracking-normal flex flex-col min-h-screen">
@include('partials.global.navigation')
<main class="container mx-auto bg-white mt-24 md:mt-18 flex-grow">
    @yield('content')
</main>
@include('partials.global.footer')
<livewire:scripts />
</body>
</html>
