<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#111827">

        <title>{{ config('app.name', 'GES') }} - Sistema de Gestión de Taller</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <script>
            // Force dark mode
            document.documentElement.classList.add('dark');
            localStorage.theme = 'dark';
        </script>
    </head>
    <body class="font-sans antialiased bg-gray-900 text-white selection:bg-indigo-500 selection:text-white overflow-x-hidden">
        
        <!-- Navbar Sticky -->
        <nav class="fixed top-0 w-full z-50 transition-all duration-300 bg-gray-900/50 backdrop-blur-md border-b border-white/10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center gap-3">
                        <img src="{{ asset('img/icons/icon.svg') }}" class="block h-9 w-auto hover:opacity-90 transition-opacity" alt="GES Logo">
                        <span class="font-bold text-xl tracking-tight text-white">GES Taller</span>
                    </div>

                    <!-- Auth Actions -->
                    <div class="flex items-center gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm font-semibold leading-6 text-white hover:text-indigo-400 transition-colors">
                                Dashboard <span aria-hidden="true">&rarr;</span>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="hidden md:inline-flex items-center justify-center px-6 py-2 border border-transparent text-sm font-medium rounded-full text-white bg-white/10 hover:bg-white/20 backdrop-blur-sm transition-all duration-200 ring-1 ring-white/20">
                                Iniciar Sesión
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-6 py-2 border border-transparent text-sm font-medium rounded-full text-white bg-indigo-600 hover:bg-indigo-500 shadow-lg shadow-indigo-500/30 transition-all duration-200">
                                    Registrarse
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="relative min-h-screen flex items-center justify-center pt-20">
            <!-- Background Image with Overlay -->
            <div class="absolute inset-0 z-0">
                <img src="{{ asset('img/workshop-hero.png') }}" class="w-full h-full object-cover" alt="Taller Background">
                <div class="absolute inset-0 bg-gray-900/40"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/40 to-transparent"></div>
                <!-- Edge Glow Effect -->
                <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-transparent via-gray-900/20 to-gray-900 opacity-80"></div>
            </div>

            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 mb-8 backdrop-blur-sm">
                    <span class="flex h-2 w-2 rounded-full bg-indigo-500"></span>
                    <span class="text-xs font-medium text-indigo-300 tracking-wide uppercase">Sistema Premium v2.0</span>
                </div>

                <!-- Title -->
                <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight text-white mb-6 leading-tight">
                    Gestión <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-cyan-300">Inteligente</span> <br>
                    para tu Taller
                </h1>

                <!-- Subtitle -->
                <p class="mt-4 text-xl text-gray-300 max-w-2xl mx-auto mb-10 leading-relaxed">
                    Optimiza cada aspecto de tu negocio automotriz. Desde la recepción digital hasta la facturación, todo en una plataforma poderosa y segura.
                </p>

                <!-- CTA -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 border border-transparent text-base font-medium rounded-xl text-white bg-indigo-600 hover:bg-indigo-500 shadow-xl shadow-indigo-600/20 transition-all duration-200 transform hover:-translate-y-1">
                            Ir al Dashboard
                            <svg class="ml-2 -mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 border border-transparent text-base font-medium rounded-xl text-white bg-indigo-600 hover:bg-indigo-500 shadow-xl shadow-indigo-600/20 transition-all duration-200 transform hover:-translate-y-1 group">
                            <span class="mr-2">Comenzar Ahora</span>
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                        <a href="#features" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 border border-white/10 text-base font-medium rounded-xl text-gray-300 bg-white/5 hover:bg-white/10 backdrop-blur-sm transition-all duration-200">
                            Conocer más
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Features Grid -->
        <div id="features" class="relative py-24 bg-gray-900 border-t border-white/5">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">Todo lo que necesitas</h2>
                    <p class="mt-4 text-lg text-gray-400">Herramientas diseñadas para maximizar la productividad de tu equipo.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="group relative p-8 bg-gray-800/50 hover:bg-gray-800 rounded-2xl border border-white/5 hover:border-indigo-500/30 transition-all duration-300">
                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 to-transparent opacity-0 group-hover:opacity-100 rounded-2xl transition-opacity"></div>
                        <div class="relative">
                            <div class="w-12 h-12 rounded-lg bg-indigo-500/10 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2">Recepción Digital</h3>
                            <p class="text-gray-400 leading-relaxed">Checklist detallado, registro fotográfico y diagrama de daños. Elimina el papel para siempre.</p>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="group relative p-8 bg-gray-800/50 hover:bg-gray-800 rounded-2xl border border-white/5 hover:border-indigo-500/30 transition-all duration-300">
                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 to-transparent opacity-0 group-hover:opacity-100 rounded-2xl transition-opacity"></div>
                        <div class="relative">
                            <div class="w-12 h-12 rounded-lg bg-emerald-500/10 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2">Kanban Interactivo</h3>
                            <p class="text-gray-400 leading-relaxed">Controla el flujo de trabajo visualmente. Arrastra y suelta órdenes entre estados personalizados.</p>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="group relative p-8 bg-gray-800/50 hover:bg-gray-800 rounded-2xl border border-white/5 hover:border-indigo-500/30 transition-all duration-300">
                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 to-transparent opacity-0 group-hover:opacity-100 rounded-2xl transition-opacity"></div>
                        <div class="relative">
                            <div class="w-12 h-12 rounded-lg bg-amber-500/10 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2">Notificaciones Auto</h3>
                            <p class="text-gray-400 leading-relaxed">Tus clientes reciben actualizaciones automáticas por email sobre el estado de su vehículo.</p>
                        </div>
                    </div>

                     <!-- Feature 4 -->
                    <div class="group relative p-8 bg-gray-800/50 hover:bg-gray-800 rounded-2xl border border-white/5 hover:border-indigo-500/30 transition-all duration-300">
                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 to-transparent opacity-0 group-hover:opacity-100 rounded-2xl transition-opacity"></div>
                        <div class="relative">
                            <div class="w-12 h-12 rounded-lg bg-purple-500/10 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2">Control de Personal</h3>
                            <p class="text-gray-400 leading-relaxed">Roles granulares para administradores, mecánicos y recepcionistas. Seguridad total.</p>
                        </div>
                    </div>

                    <!-- Feature 5 -->
                    <div class="group relative p-8 bg-gray-800/50 hover:bg-gray-800 rounded-2xl border border-white/5 hover:border-indigo-500/30 transition-all duration-300">
                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 to-transparent opacity-0 group-hover:opacity-100 rounded-2xl transition-opacity"></div>
                        <div class="relative">
                            <div class="w-12 h-12 rounded-lg bg-pink-500/10 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2">Multi-Sucursal</h3>
                            <p class="text-gray-400 leading-relaxed">Gestiona múltiples talleres desde un solo panel. Datos aislados y reportes consolidados.</p>
                        </div>
                    </div>

                    <!-- Feature 6 -->
                    <div class="group relative p-8 bg-gray-800/50 hover:bg-gray-800 rounded-2xl border border-white/5 hover:border-indigo-500/30 transition-all duration-300">
                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 to-transparent opacity-0 group-hover:opacity-100 rounded-2xl transition-opacity"></div>
                        <div class="relative">
                            <div class="w-12 h-12 rounded-lg bg-cyan-500/10 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2">Rastreo Web</h3>
                            <p class="text-gray-400 leading-relaxed">Portal público para que tus clientes consulten el estado de su vehículo solo con la placa.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-950 py-12 border-t border-white/5">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
                <!-- Copyright -->
                <div class="text-gray-500 text-sm">
                    &copy; {{ date('Y') }} GES Taller. Todos los derechos reservados.
                </div>

                <!-- Credits -->
                <div class="flex items-center gap-2 text-sm text-gray-500">
                    <span>Desarrollado con</span>
                    <svg class="w-4 h-4 text-red-500 fill-current" viewBox="0 0 20 20">
                        <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/>
                    </svg>
                    <span>y Laravel {{ Illuminate\Foundation\Application::VERSION }}</span>
                </div>
            </div>
        </footer>

        @stack('modals')
        @livewireScripts
        <x-pwa-install-prompt />
    </body>
</html>
