<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    @livewireStyles
    @stack('styles')
</head>
<body>
    {{ $slot }}
    @livewireScripts
</body>
</html>
