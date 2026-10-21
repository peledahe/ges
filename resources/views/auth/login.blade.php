<x-guest-layout>
    <div x-data="{
        email: '{{ old('email', '') }}',
        password: '',
        showPassword: false,
        activeDemo: null,
        fillCredentials(userEmail, userPass, demoType) {
            this.email = userEmail;
            this.password = userPass;
            this.activeDemo = demoType;
            let emailInput = document.getElementById('email');
            let passInput = document.getElementById('password');
            if (emailInput) {
                emailInput.value = userEmail;
                emailInput.dispatchEvent(new Event('input'));
            }
            if (passInput) {
                passInput.value = userPass;
                passInput.dispatchEvent(new Event('input'));
            }
        }
    }" class="w-full max-w-5xl my-4 sm:my-8 bg-white dark:bg-slate-900 rounded-[2rem] border border-gray-200 dark:border-slate-800 shadow-2xl overflow-hidden backdrop-blur-xl grid grid-cols-1 lg:grid-cols-12 transition-all">

        <!-- ========================================== -->
        <!-- ========================================== -->
        <!-- COLUMNA IZQUIERDA: RESUMEN CONCISO         -->
        <!-- ========================================== -->
        <div class="lg:col-span-5 bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 text-white p-8 sm:p-10 flex flex-col justify-between relative overflow-hidden">
            <!-- Efectos de luces y formas abstractas -->
            <div class="absolute -top-24 -left-24 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-purple-500/20 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 space-y-6">
                <!-- Logo & Nombre -->
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-white/15 backdrop-blur-md border border-white/20 flex items-center justify-center p-2 shadow-inner flex-shrink-0">
                        <img src="{{ asset('img/icons/icon.svg') }}" alt="GES Taller" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <div class="font-black text-2xl tracking-tight leading-none text-white">GES Taller</div>
                        <div class="text-[10px] uppercase tracking-widest text-indigo-200 font-extrabold mt-1">GESTIÓN AUTOMOTRIZ</div>
                    </div>
                </div>

                <!-- Título conciso -->
                <div class="space-y-2 pt-2">
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight leading-tight text-white">
                        Control total de tu taller mecánico
                    </h1>
                    <p class="text-xs sm:text-sm text-indigo-100/90 leading-relaxed font-normal">
                        Gestiona recepción, reparaciones, cotizaciones y cobros en un solo lugar.
                    </p>
                </div>

                <!-- Lista compacta y limpia: 4 cápsulas directas -->
                <div class="space-y-2.5 pt-1">
                    <div class="flex items-center gap-3 p-2.5 rounded-xl bg-white/10 backdrop-blur-md border border-white/10">
                        <span class="text-base flex-shrink-0">📋</span>
                        <span class="text-xs font-semibold text-white">Recepción digital con checklist y daños</span>
                    </div>

                    <div class="flex items-center gap-3 p-2.5 rounded-xl bg-white/10 backdrop-blur-md border border-white/10">
                        <span class="text-base flex-shrink-0">🛠️</span>
                        <span class="text-xs font-semibold text-white">Tablero Kanban operativo por áreas</span>
                    </div>

                    <div class="flex items-center gap-3 p-2.5 rounded-xl bg-white/10 backdrop-blur-md border border-white/10">
                        <span class="text-base flex-shrink-0">💰</span>
                        <span class="text-xs font-semibold text-white">Presupuestos y cobros multimoneda (Q / $)</span>
                    </div>

                    <div class="flex items-center gap-3 p-2.5 rounded-xl bg-white/10 backdrop-blur-md border border-white/10">
                        <span class="text-base flex-shrink-0">🔍</span>
                        <span class="text-xs font-semibold text-white">Rastreo de estado por placa en vivo</span>
                    </div>
                </div>
            </div>

            <!-- Footer Izquierdo Simple -->
            <div class="relative z-10 pt-6 mt-6 border-t border-white/15 flex items-center justify-between text-[11px] text-indigo-200">
                <span>Multi-taller en la nube</span>
                <span class="font-bold text-white flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    GES Platform
                </span>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- COLUMNA DERECHA: FORMULARIO & DEMO ACCESS  -->
        <!-- ========================================== -->
        <div class="lg:col-span-7 p-8 sm:p-10 lg:p-12 flex flex-col justify-center bg-white dark:bg-slate-900">
            <div>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                    Iniciar Sesión
                </h2>
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Ingresa tus credenciales para acceder a la consola.
                </p>
            </div>

            <!-- TARJETA DE ACCESOS RÁPIDOS DEMO -->
            @php
                $demoUsers = \App\Models\User::where('is_demo', true)->get()->keyBy('email');
                $adminDemoPass = $demoUsers['admin@taller-demo.com']->demo_password ?? '';
                $receptionDemoPass = $demoUsers['recepcion@taller-demo.com']->demo_password ?? '';
                $mechanicDemoPass = $demoUsers['mecanico@taller-demo.com']->demo_password ?? '';
                $clientDemoPass = $demoUsers['cliente@taller-demo.com']->demo_password ?? '';
            @endphp
            <div class="mt-5 p-4 rounded-2xl bg-indigo-50/70 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-800/60 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-black text-indigo-700 dark:text-indigo-400 uppercase tracking-wider flex items-center gap-1.5">
                        <span>🔑</span>
                        <span>ACCESOS RÁPIDOS DEMO</span>
                    </span>
                    <span class="text-[11px] text-gray-400 dark:text-gray-400">
                        Clic para autocompletar
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <!-- Botón Demo Admin -->
                    <button type="button"
                            @click="fillCredentials('admin@taller-demo.com', '{{ $adminDemoPass }}', 'admin')"
                            :class="activeDemo === 'admin' ? 'ring-2 ring-indigo-500 bg-white dark:bg-slate-800' : 'bg-white/80 dark:bg-slate-900/80 hover:bg-white dark:hover:bg-slate-800'"
                            class="flex items-center gap-2 p-2 rounded-xl border border-indigo-200/60 dark:border-indigo-800/60 text-left transition-all group">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-500 flex-shrink-0 group-hover:scale-125 transition-transform"></span>
                        <div class="min-w-0 flex-1">
                            <span class="block text-xs font-bold text-gray-800 dark:text-gray-200">Admin</span>
                            <span class="block text-[11px] font-mono text-indigo-600 dark:text-indigo-400 truncate">admin@taller-demo.com</span>
                        </div>
                    </button>

                    <!-- Botón Demo Recepción -->
                    <button type="button"
                            @click="fillCredentials('recepcion@taller-demo.com', '{{ $receptionDemoPass }}', 'reception')"
                            :class="activeDemo === 'reception' ? 'ring-2 ring-indigo-500 bg-white dark:bg-slate-800' : 'bg-white/80 dark:bg-slate-900/80 hover:bg-white dark:hover:bg-slate-800'"
                            class="flex items-center gap-2 p-2 rounded-xl border border-indigo-200/60 dark:border-indigo-800/60 text-left transition-all group">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 flex-shrink-0 group-hover:scale-125 transition-transform"></span>
                        <div class="min-w-0 flex-1">
                            <span class="block text-xs font-bold text-gray-800 dark:text-gray-200">Recepción</span>
                            <span class="block text-[11px] font-mono text-emerald-600 dark:text-emerald-400 truncate">recepcion@taller-demo.com</span>
                        </div>
                    </button>

                    <!-- Botón Demo Mecánico / Jefe de Área -->
                    <button type="button"
                            @click="fillCredentials('mecanico@taller-demo.com', '{{ $mechanicDemoPass }}', 'mecanico')"
                            :class="activeDemo === 'mecanico' ? 'ring-2 ring-indigo-500 bg-white dark:bg-slate-800' : 'bg-white/80 dark:bg-slate-900/80 hover:bg-white dark:hover:bg-slate-800'"
                            class="flex items-center gap-2 p-2 rounded-xl border border-indigo-200/60 dark:border-indigo-800/60 text-left transition-all group">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500 flex-shrink-0 group-hover:scale-125 transition-transform"></span>
                        <div class="min-w-0 flex-1">
                            <span class="block text-xs font-bold text-gray-800 dark:text-gray-200">Técnico / Mecánico</span>
                            <span class="block text-[11px] font-mono text-amber-600 dark:text-amber-400 truncate">mecanico@taller-demo.com</span>
                        </div>
                    </button>

                    <!-- Botón Demo Cliente -->
                    <button type="button"
                            @click="fillCredentials('cliente@taller-demo.com', '{{ $clientDemoPass }}', 'cliente')"
                            :class="activeDemo === 'cliente' ? 'ring-2 ring-indigo-500 bg-white dark:bg-slate-800' : 'bg-white/80 dark:bg-slate-900/80 hover:bg-white dark:hover:bg-slate-800'"
                            class="flex items-center gap-2 p-2 rounded-xl border border-indigo-200/60 dark:border-indigo-800/60 text-left transition-all group">
                        <span class="w-2.5 h-2.5 rounded-full bg-purple-500 flex-shrink-0 group-hover:scale-125 transition-transform"></span>
                        <div class="min-w-0 flex-1">
                            <span class="block text-xs font-bold text-gray-800 dark:text-gray-200">Cliente</span>
                            <span class="block text-[11px] font-mono text-purple-600 dark:text-purple-400 truncate">cliente@taller-demo.com</span>
                        </div>
                    </button>
                </div>

                <!-- Nota Informativa de Seguridad Demo (sin acceso root) -->
                <div class="mt-2.5 pt-2 border-t border-indigo-200/40 dark:border-indigo-800/40 flex items-center justify-center text-[11px] text-gray-500 dark:text-gray-400">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <span>Cuentas demo con claves aleatorias seguras protegidas contra modificaciones.</span>
                    </span>
                </div>
            </div>

            <x-validation-errors class="mt-4 mb-2" />

            @session('status')
                <div class="mt-4 mb-2 font-medium text-xs text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 p-3 rounded-xl border border-emerald-200 dark:border-emerald-800">
                    {{ $value }}
                </div>
            @endsession

            <!-- FORMULARIO DE ACCESO -->
            <form method="POST" action="{{ route('login') }}" class="mt-4 space-y-4">
                @csrf

                <!-- Campo Correo Electrónico -->
                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">
                        {{ __('Correo Electrónico') }}
                    </label>
                    <div class="relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input id="email"
                               type="email"
                               name="email"
                               x-model="email"
                               value="{{ old('email') }}"
                               required
                               autofocus
                               autocomplete="username"
                               placeholder="tu@correo.com"
                               class="pl-10 pr-4 py-3 bg-gray-50/70 dark:bg-slate-800/80 border border-gray-300 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm w-full font-medium text-gray-900 dark:text-white placeholder-gray-400 transition" />
                    </div>
                </div>

                <!-- Campo Contraseña -->
                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">
                        {{ __('Contraseña') }}
                    </label>
                    <div class="relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input id="password"
                               :type="showPassword ? 'text' : 'password'"
                               name="password"
                               x-model="password"
                               required
                               autocomplete="current-password"
                               placeholder="••••••••••••"
                               class="pl-10 pr-11 py-3 bg-gray-50/70 dark:bg-slate-800/80 border border-gray-300 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm w-full font-medium text-gray-900 dark:text-white placeholder-gray-400 transition" />
                        
                        <!-- Toggle Ver/Ocultar contraseña -->
                        <button type="button"
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition focus:outline-none">
                            <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="showPassword" style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Recordarme & Olvidé contraseña -->
                <div class="flex items-center justify-between pt-1">
                    <label for="remember_me" class="flex items-center cursor-pointer">
                        <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-slate-700 bg-gray-50 dark:bg-slate-800">
                        <span class="ms-2 text-xs font-medium text-gray-600 dark:text-gray-400">{{ __('Recordarme') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition" href="{{ route('password.request') }}">
                            {{ __('¿Olvidaste tu contraseña?') }}
                        </a>
                    @endif
                </div>

                <!-- Botón Principal Iniciar Sesión -->
                <div class="pt-2">
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 py-3.5 px-6 rounded-xl text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 shadow-lg shadow-indigo-600/30 transition-all transform active:scale-[0.99] cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        <span>{{ __('Iniciar sesión') }}</span>
                    </button>
                </div>

                <!-- Enlace a Registro de Nuevo Taller -->
                <div class="pt-2 text-center">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        ¿Quieres registrar tu propio taller?
                        <a href="{{ route('register') }}" class="font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                            Regístrate gratis aquí (15 días de prueba)
                        </a>
                    </p>
                </div>

                <!-- Acceso Directo a Rastreo por Placa sin Login -->
                <div class="pt-4 border-t border-gray-200 dark:border-slate-800 text-center">
                    <a href="{{ route('rastreo') }}" class="inline-flex items-center gap-2 text-xs font-bold text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 transition group">
                        <span class="p-1 rounded-lg bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition-transform">🔍</span>
                        <span>¿Tienes un vehículo en el taller? <strong class="underline decoration-indigo-400 underline-offset-4">Consulta por placa sin ingresar →</strong></span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
