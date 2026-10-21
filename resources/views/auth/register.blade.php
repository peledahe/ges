<x-guest-layout>
    <div x-data="{
        showPassword: false,
        showConfirmPassword: false
    }" class="w-full max-w-5xl my-4 sm:my-8 bg-white dark:bg-slate-900 rounded-[2rem] border border-gray-200 dark:border-slate-800 shadow-2xl overflow-hidden backdrop-blur-xl grid grid-cols-1 lg:grid-cols-12 transition-all">

        <!-- ========================================== -->
        <!-- COLUMNA IZQUIERDA: BENEFICIOS & PRUEBA 15 DÍAS -->
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

                <!-- Insignia destacada de 15 días gratis -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-500/20 border border-emerald-400/40 text-emerald-200 text-xs font-black shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>15 DÍAS DE PRUEBA GRATIS</span>
                </div>

                <!-- Título conciso -->
                <div class="space-y-2 pt-1">
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight leading-tight text-white">
                        Digitaliza tu taller en minutos
                    </h1>
                    <p class="text-xs sm:text-sm text-indigo-100/90 leading-relaxed font-normal">
                        Crea tu espacio de trabajo exclusivo con tu usuario administrador y tablero operativo preconfigurado.
                    </p>
                </div>

                <!-- Lista de beneficios incluidos -->
                <div class="space-y-2.5 pt-1">
                    <div class="flex items-center gap-3 p-2.5 rounded-xl bg-white/10 backdrop-blur-md border border-white/10">
                        <span class="text-base flex-shrink-0">🏢</span>
                        <span class="text-xs font-semibold text-white">Tu taller aislado con enlace web propio</span>
                    </div>

                    <div class="flex items-center gap-3 p-2.5 rounded-xl bg-white/10 backdrop-blur-md border border-white/10">
                        <span class="text-base flex-shrink-0">🛠️</span>
                        <span class="text-xs font-semibold text-white">6 Áreas Kanban recomendadas precargadas</span>
                    </div>

                    <div class="flex items-center gap-3 p-2.5 rounded-xl bg-white/10 backdrop-blur-md border border-white/10">
                        <span class="text-base flex-shrink-0">📋</span>
                        <span class="text-xs font-semibold text-white">Recepción digital con marcado de daños</span>
                    </div>

                    <div class="flex items-center gap-3 p-2.5 rounded-xl bg-white/10 backdrop-blur-md border border-white/10">
                        <span class="text-base flex-shrink-0">✉️</span>
                        <span class="text-xs font-semibold text-white">Confirmación y bienvenida por correo</span>
                    </div>
                </div>
            </div>

            <!-- Footer Izquierdo Simple -->
            <div class="relative z-10 pt-6 mt-6 border-t border-white/15 flex items-center justify-between text-[11px] text-indigo-200">
                <span>Sin tarjeta de crédito requerida</span>
                <span class="font-bold text-white flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    Activación Inmediata
                </span>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- COLUMNA DERECHA: FORMULARIO DE REGISTRO     -->
        <!-- ========================================== -->
        <div class="lg:col-span-7 p-8 sm:p-10 lg:p-12 flex flex-col justify-center bg-white dark:bg-slate-900">
            <div>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                    Registrar Nuevo Taller
                </h2>
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Crea tu cuenta de taller y tu usuario administrador para comenzar.
                </p>
            </div>

            <x-validation-errors class="mt-4" />

            <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
                @csrf

                <!-- Nombre de la Empresa o Taller -->
                <div>
                    <label for="workshop_name" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                        Nombre de la Empresa o Taller <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative mt-1">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            🏢
                        </div>
                        <input id="workshop_name"
                               name="workshop_name"
                               type="text"
                               value="{{ old('workshop_name') }}"
                               required
                               autofocus
                               placeholder="Ej: Taller Mecánico Los Andes"
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm transition" />
                    </div>
                </div>

                <!-- Nombre del Administrador -->
                <div>
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                        Nombre del Administrador <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative mt-1">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            👤
                        </div>
                        <input id="name"
                               name="name"
                               type="text"
                               value="{{ old('name') }}"
                               required
                               autocomplete="name"
                               placeholder="Tu nombre y apellido"
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm transition" />
                    </div>
                </div>

                <!-- Correo Electrónico y Teléfono en 2 columnas -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                            Correo Electrónico <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative mt-1">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                ✉️
                            </div>
                            <input id="email"
                                   name="email"
                                   type="email"
                                   value="{{ old('email') }}"
                                   required
                                   autocomplete="username"
                                   placeholder="correo@ejemplo.com"
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm transition" />
                        </div>
                    </div>

                    <div>
                        <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                            Teléfono (Opcional)
                        </label>
                        <div class="relative mt-1">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                📞
                            </div>
                            <input id="phone"
                                   name="phone"
                                   type="text"
                                   value="{{ old('phone') }}"
                                   placeholder="Ej: 5555-1234"
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm transition" />
                        </div>
                    </div>
                </div>

                <!-- Contraseñas en 2 columnas -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                            Contraseña <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative mt-1">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                🔒
                            </div>
                            <input id="password"
                                   name="password"
                                   :type="showPassword ? 'text' : 'password'"
                                   required
                                   autocomplete="new-password"
                                   placeholder="Mínimo 8 caracteres"
                                   class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-gray-300 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm transition" />
                            <button type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                <span x-show="!showPassword" class="text-xs">👁️</span>
                                <span x-show="showPassword" style="display: none;" class="text-xs">🙈</span>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                            Confirmar Contraseña <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative mt-1">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                🔒
                            </div>
                            <input id="password_confirmation"
                                   name="password_confirmation"
                                   :type="showConfirmPassword ? 'text' : 'password'"
                                   required
                                   autocomplete="new-password"
                                   placeholder="Repite la contraseña"
                                   class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-gray-300 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm transition" />
                            <button type="button"
                                    @click="showConfirmPassword = !showConfirmPassword"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                <span x-show="!showConfirmPassword" class="text-xs">👁️</span>
                                <span x-show="showConfirmPassword" style="display: none;" class="text-xs">🙈</span>
                            </button>
                        </div>
                    </div>
                </div>

                @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                    <div class="mt-2">
                        <label for="terms" class="flex items-center">
                            <x-checkbox name="terms" id="terms" required />
                            <div class="ms-2 text-xs text-gray-600 dark:text-gray-400">
                                Acepto los <a target="_blank" href="{{ route('terms.show') }}" class="underline hover:text-gray-900 dark:hover:text-white">Términos del Servicio</a> y la <a target="_blank" href="{{ route('policy.show') }}" class="underline hover:text-gray-900 dark:hover:text-white">Política de Privacidad</a>.
                            </div>
                        </label>
                    </div>
                @endif

                <!-- Botón de Registro -->
                <div class="pt-2">
                    <button type="submit"
                            class="w-full py-3 px-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 active:from-indigo-800 active:to-purple-800 text-white font-black text-sm rounded-xl shadow-lg shadow-indigo-500/25 transition-all transform hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center gap-2">
                        <span>🚀 Crear Taller y Comenzar Prueba (15 Días Gratis)</span>
                    </button>
                </div>

                <!-- Enlace a Login -->
                <div class="pt-3 text-center">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        ¿Ya tienes una cuenta registrada?
                        <a href="{{ route('login') }}" class="font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                            Iniciar Sesión aquí
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
