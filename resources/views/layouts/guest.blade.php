<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
        
        <!-- PWA -->
        <link rel="manifest" href="{{ asset('manifest.json') }}">
        <meta name="theme-color" content="#4f46e5"> 
        <link rel="apple-touch-icon" href="{{ asset('img/icons/icon.svg') }}">
        <link rel="icon" href="{{ asset('img/icons/icon.svg') }}" type="image/svg+xml">

        <script>
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('/sw.js');
            }
        </script>
    </head>
    <body class="font-sans text-gray-900 bg-gray-900 antialiased dark">
        <!-- Background Image with Overlay -->
        <div class="fixed inset-0 z-0">
            <img src="{{ asset('img/workshop-hero.png') }}" class="w-full h-full object-cover" alt="Background">
            <div class="absolute inset-0 bg-gray-900/90 mix-blend-multiply"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/40 to-transparent"></div>
        </div>

        <div class="relative z-10 min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            {{ $slot }}
        </div>

        @livewireScripts
    </body>
</html>
