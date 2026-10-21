<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'GES - Gestión Automotriz') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Theme Initialization -->
        <script>
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>

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
    <body class="font-sans text-gray-900 dark:text-gray-100 bg-[#070b14] antialiased min-h-screen relative overflow-x-hidden selection:bg-indigo-500 selection:text-white">
        
        <!-- Fondo con efecto de red / malla tecnológica y gradiente sutil -->
        <div class="fixed inset-0 z-0 pointer-events-none">
            <div class="absolute inset-0 bg-[#070b14]"></div>
            <!-- Efecto radial sutil -->
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[900px] h-[600px] bg-indigo-600/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute top-1/4 right-1/4 w-[400px] h-[400px] bg-purple-600/10 rounded-full blur-3xl pointer-events-none"></div>
            <!-- Malla abstracta de líneas tenues -->
            <svg class="absolute inset-0 w-full h-full opacity-10 [mask-image:radial-gradient(ellipse_at_center,white,transparent_75%)]" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="guest-grid" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M 40 0 L 0 0 0 40" fill="none" stroke="currentColor" stroke-width="0.75" class="text-indigo-400" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#guest-grid)" />
            </svg>
        </div>

        <!-- Barra superior con botón de cambio de tema (Modo oscuro / claro) -->
        <div class="relative z-20 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 flex justify-end">
            <div x-data="{
                darkMode: localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
                toggle() {
                    this.darkMode = !this.darkMode;
                    if (this.darkMode) {
                        document.documentElement.classList.add('dark');
                        localStorage.theme = 'dark';
                    } else {
                        document.documentElement.classList.remove('dark');
                        localStorage.theme = 'light';
                    }
                }
            }">
                <button @click="toggle()" type="button" class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-gray-900/80 hover:bg-gray-800 text-gray-300 border border-gray-700/80 shadow-lg backdrop-blur-md transition-all hover:scale-105 active:scale-95">
                    <span x-show="darkMode" class="text-amber-400">🌙</span>
                    <span x-show="!darkMode" style="display: none;" class="text-amber-400">☀️</span>
                    <span x-text="darkMode ? 'Modo oscuro' : 'Modo claro'">Modo oscuro</span>
                </button>
            </div>
        </div>

        <div class="relative z-10 min-h-[calc(100vh-60px)] flex flex-col justify-center items-center px-4 py-8 sm:px-6 lg:px-8">
            {{ $slot }}
        </div>

        @livewireScripts
    </body>
</html>
