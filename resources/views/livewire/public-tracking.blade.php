<div class="w-full max-w-4xl mx-auto space-y-6 my-auto">
    
    <!-- Barra Superior con Enlace a Inicio / Login -->
    <div class="flex items-center justify-between px-2">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-gray-900 dark:hover:text-white transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Volver al Inicio</span>
        </a>

        @auth
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                <span>Ir a mi Dashboard</span>
                <span>→</span>
            </a>
        @else
            <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                <span>Iniciar Sesión</span>
                <span>→</span>
            </a>
        @endauth
    </div>

    <!-- Encabezado y Formulario de Búsqueda -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 sm:p-10 shadow-2xl border border-gray-200/80 dark:border-gray-700/80 text-center relative overflow-hidden backdrop-blur-sm">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10">
            @if ($tenant)
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs font-bold mb-3 shadow-xs">
                    <span>🏢</span>
                    <span>Portal Oficial de Rastreo: <strong>{{ $tenant->name }}</strong></span>
                </div>
            @else
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-200 dark:border-indigo-800 text-indigo-700 dark:text-indigo-300 text-xs font-bold uppercase tracking-wider mb-3">
                    <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <span>Consulta Pública Sin Iniciar Sesión</span>
                </div>
            @endif

            <h1 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white tracking-tight">
                @if ($tenant)
                    Estatus de Vehículo • {{ $tenant->name }}
                @else
                    Rastreo y Estatus de Vehículo
                @endif
            </h1>
            <p class="mt-2 text-xs sm:text-sm text-gray-500 dark:text-gray-400 max-w-lg mx-auto">
                @if ($tenant)
                    Ingresa el número de placa de tu vehículo registrado en <strong>{{ $tenant->name }}</strong> para consultar avances, cotización y entrega.
                @else
                    Ingresa el número de placa de tu vehículo para consultar su etapa actual en el taller, cotización, pagos registrados y avances técnicos.
                @endif
            </p>

            <!-- Formulario de Consulta -->
            <form wire:submit.prevent="search" class="mt-6 max-w-md mx-auto">
                <div class="flex flex-col sm:flex-row items-center gap-2.5">
                    <div class="relative w-full">
                        <input wire:model="plate"
                               type="text"
                               required
                               placeholder="Ej: P-123XYZ o 456ABC"
                               class="w-full text-center sm:text-left uppercase font-mono font-bold tracking-wider px-4 py-3 rounded-2xl border-2 border-indigo-200 dark:border-indigo-700/80 bg-gray-50/50 dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-600 text-base shadow-inner">
                    </div>
                    <button type="submit"
                            wire:loading.attr="disabled"
                            class="w-full sm:w-auto px-7 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-sm rounded-2xl shadow-lg shadow-indigo-600/30 transition-all flex items-center justify-center gap-2 flex-shrink-0 cursor-pointer">
                        <span wire:loading.remove>Consultar</span>
                        <span wire:loading>Buscando...</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>
                @error('plate')
                    <span class="text-rose-500 text-xs mt-2 block font-semibold text-center">{{ $message }}</span>
                @enderror
            </form>
        </div>
    </div>

    <!-- RESULTADOS DE LA BÚSQUEDA -->
    @if ($searched)
        @if ($workOrder)
            @php
                $stages = [
                    'recibido' => ['label' => 'Recepción', 'step' => 1],
                    'presupuesto' => ['label' => 'Presupuesto', 'step' => 2],
                    'en_espera' => ['label' => 'En Espera', 'step' => 3],
                    'trabajando' => ['label' => 'En Proceso', 'step' => 4],
                    'revision' => ['label' => 'Control de Calidad', 'step' => 5],
                    'terminado' => ['label' => 'Listo para Entrega', 'step' => 6],
                    'entregado' => ['label' => 'Entregado', 'step' => 7],
                ];
                $currentStep = $stages[$workOrder->status]['step'] ?? 1;
            @endphp

            <div class="space-y-6">
                
                <!-- Tarjeta Principal del Vehículo -->
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 sm:p-8 shadow-xl border border-gray-200/80 dark:border-gray-700 space-y-6">
                    
                    <!-- Encabezado con Placa Estilo Matrícula y Estado -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-gray-100 dark:border-gray-700">
                        <div class="flex items-center gap-4">
                            <span class="text-xl font-mono font-black tracking-widest bg-amber-300 text-gray-900 px-4 py-2 rounded-xl border-2 border-gray-900 shadow-md">
                                {{ $workOrder->vehicle?->plate }}
                            </span>
                            <div>
                                <h2 class="text-xl font-black text-gray-900 dark:text-white">
                                    {{ $workOrder->vehicle?->brand }} {{ $workOrder->vehicle?->model }}
                                    <span class="text-gray-400 font-normal text-sm">({{ $workOrder->vehicle?->year ?? 'Año N/D' }})</span>
                                </h2>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                    Color: {{ $workOrder->vehicle?->color ?? 'N/D' }} • 
                                    @if (($workOrder->vehicle?->fuel_type ?? '') === 'electrico')
                                        ⚡ Motor Eléctrico
                                    @elseif (($workOrder->vehicle?->fuel_type ?? '') === 'diesel')
                                        🛢️ Motor Diésel
                                    @else
                                        ⛽ Motor Gasolina
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            @if ($workOrder->area)
                                <span class="text-xs font-bold px-3 py-1 rounded-full bg-purple-50 text-purple-700 dark:bg-purple-950/60 dark:text-purple-300 border border-purple-200 dark:border-purple-800">
                                    📍 Área: {{ $workOrder->area->name }}
                                </span>
                            @endif

                            @if ($workOrder->status === 'entregado')
                                <span class="text-xs font-black px-3.5 py-1 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-300">
                                    ✓ Vehículo Entregado
                                </span>
                            @else
                                <span class="text-xs font-black px-3.5 py-1 rounded-full bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800 flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-indigo-600 animate-pulse"></span>
                                    <span>{{ ucfirst(str_replace('_', ' ', $workOrder->status)) }}</span>
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- LÍNEA DE TIEMPO / PROGRESO DE ETAPAS -->
                    <div>
                        <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider block mb-3">
                            Etapa de Reparación en el Taller
                        </span>
                        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-2">
                            @foreach ($stages as $stKey => $stData)
                                @php
                                    $isPast = $currentStep >= $stData['step'];
                                    $isCurrent = $workOrder->status === $stKey;
                                @endphp
                                <div class="p-2.5 rounded-xl border text-center transition {{ $isCurrent ? 'bg-indigo-600 text-white border-indigo-600 shadow-md ring-2 ring-indigo-300 dark:ring-indigo-800' : ($isPast ? 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 border-indigo-200 dark:border-indigo-800' : 'bg-gray-50 dark:bg-gray-900/40 text-gray-400 border-gray-200 dark:border-gray-800') }}">
                                    <span class="text-[10px] font-mono block opacity-80">{{ $stData['step'] }}</span>
                                    <span class="text-[11px] font-bold block truncate mt-0.5">{{ $stData['label'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- GRID: COTIZACIÓN / PAGOS & DETALLE DEL TRABAJO -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        
                        <!-- INFORMACIÓN FINANCIERA: COTIZACIÓN Y PAGOS -->
                        <div class="p-5 bg-gray-50 dark:bg-gray-900/60 rounded-2xl border border-gray-200/80 dark:border-gray-700 flex flex-col justify-between">
                            <div>
                                <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider block mb-3 flex items-center gap-1.5">
                                    <span>💰</span>
                                    <span>Cotización y Estado Financiero</span>
                                </span>

                                <div class="space-y-2.5 text-xs">
                                    <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                                        <span class="text-gray-500 dark:text-gray-400 font-bold">Total Cotizado:</span>
                                        <span class="text-base font-black text-gray-900 dark:text-white font-mono">
                                            {{ $workOrder->total_amount ? \App\Helpers\CurrencyHelper::formatDual($workOrder->total_amount) : 'En formulación técnica' }}
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                                        <span class="text-gray-500 dark:text-gray-400 font-bold">Abonado / Pagado:</span>
                                        <span class="text-base font-black text-emerald-600 dark:text-emerald-400 font-mono">
                                            @currencyDual($workOrder->paid_amount ?? 0)
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                                        <span class="text-gray-500 dark:text-gray-400 font-bold">Saldo Pendiente:</span>
                                        <span class="text-base font-black font-mono {{ $workOrder->pendingPaymentAmount() > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600' }}">
                                            @if($workOrder->pendingPaymentAmount() > 0)
                                                @currencyDual($workOrder->pendingPaymentAmount())
                                            @else
                                                ✓ Solvente
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between text-xs">
                                <span class="text-gray-500 dark:text-gray-400 font-bold">Estado del Pago:</span>
                                @if ($workOrder->payment_status === 'pagado')
                                    <span class="font-black text-emerald-700 bg-emerald-100 dark:bg-emerald-950/60 dark:text-emerald-300 px-3 py-1 rounded-full border border-emerald-300">
                                        ✓ Liquidado / Pagado
                                    </span>
                                @elseif ($workOrder->payment_status === 'parcial')
                                    <span class="font-black text-amber-700 bg-amber-100 dark:bg-amber-950/60 dark:text-amber-300 px-3 py-1 rounded-full border border-amber-300">
                                        🟡 Pago Parcial
                                    </span>
                                @else
                                    <span class="font-bold text-gray-500 bg-gray-200 dark:bg-gray-700 px-3 py-1 rounded-full">
                                        💳 Pendiente de Pago
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- DETALLE DEL TRABAJO Y ANOMALÍAS -->
                        <div class="p-5 bg-gray-50 dark:bg-gray-900/60 rounded-2xl border border-gray-200/80 dark:border-gray-700 flex flex-col justify-between">
                            <div>
                                <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider block mb-2 flex items-center gap-1.5">
                                    <span>🛠️</span>
                                    <span>Trabajo Solicitado / Servicios</span>
                                </span>
                                <p class="text-xs text-gray-800 dark:text-gray-200 whitespace-pre-line leading-relaxed bg-white dark:bg-gray-800 p-3 rounded-xl border border-gray-200 dark:border-gray-700">
                                    {{ $workOrder->notes ?: 'Inspección técnica general y mantenimiento según hoja de recepción.' }}
                                </p>

                                <!-- Novedades de Inspección -->
                                @if ($workOrder->checklists && $workOrder->checklists->where('status', '!=', 'correct')->isNotEmpty())
                                    <div class="mt-3">
                                        <span class="text-[11px] font-bold text-amber-700 dark:text-amber-400 block mb-1.5 flex items-center gap-1">
                                            <span>📋</span>
                                            <span>Anomalías reportadas en recepción:</span>
                                        </span>
                                        <div class="space-y-1.5 max-h-36 overflow-y-auto text-xs pr-1">
                                            @foreach ($workOrder->checklists->where('status', '!=', 'correct') as $chk)
                                                <div class="flex items-center justify-between p-2 rounded-lg bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-900/40 text-[11px]">
                                                    <span class="font-bold text-gray-900 dark:text-white">{{ $chk->group_name }}: {{ $chk->item_name }}</span>
                                                    <span class="font-black uppercase text-[10px] text-rose-700 dark:text-rose-300">
                                                        {{ $chk->status }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-4 pt-3 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between text-xs text-gray-400 font-mono">
                                <span>Ingreso: {{ $workOrder->created_at->format('d/m/Y H:i') }}</span>
                                <span>Orden #{{ $workOrder->id }}</span>
                            </div>
                        </div>

                    </div>

                    <!-- CONTACTO DIRECTO CON EL TALLER -->
                    @if ($tenant)
                        <div class="p-4 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-950/40 dark:to-blue-950/40 rounded-2xl border border-indigo-200 dark:border-indigo-800/60 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <span class="text-2xl">🏢</span>
                                <div>
                                    <span class="font-black text-sm text-gray-900 dark:text-white block">{{ $tenant->name }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 block">{{ $tenant->address ?? 'Atención personalizada en taller' }}</span>
                                </div>
                            </div>

                            @if ($tenant->phone)
                                <div class="flex items-center gap-2">
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $tenant->phone) }}?text=Hola,%20quisiera%20consultar%20sobre%20mi%20vehículo%20placa%20{{ $workOrder->vehicle?->plate }}"
                                       target="_blank"
                                       class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow transition flex items-center gap-1.5">
                                        <span>💬</span>
                                        <span>Consultar por WhatsApp</span>
                                    </a>

                                    <a href="tel:{{ $tenant->phone }}"
                                       class="px-3.5 py-2 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 font-bold text-xs rounded-xl border border-gray-300 dark:border-gray-600 hover:bg-gray-100 transition flex items-center gap-1.5">
                                        <span>📞</span>
                                        <span>{{ $tenant->phone }}</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif

                </div>

            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 sm:p-12 shadow-xl border border-gray-200/80 dark:border-gray-700 text-center max-w-lg mx-auto">
                <span class="text-4xl block mb-3">🔍</span>
                <h3 class="text-lg font-black text-gray-900 dark:text-white">
                    Vehículo no encontrado
                </h3>
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-2">
                    @if ($tenant)
                        No encontramos ninguna orden de trabajo asociada a la placa <strong class="text-gray-900 dark:text-white font-mono">{{ $plate }}</strong> en <strong>{{ $tenant->name }}</strong>.
                    @else
                        No encontramos ninguna orden de trabajo asociada a la placa <strong class="text-gray-900 dark:text-white font-mono">{{ $plate }}</strong>.
                    @endif
                </p>
                <p class="text-xs text-gray-400 mt-2">
                    Por favor, verifica que la placa esté escrita correctamente o comunícate con el taller para confirmar el ingreso.
                </p>
            </div>
        @endif
    @endif

    <!-- Footer sutil -->
    <div class="pt-6 text-center text-xs text-gray-400">
        <p>© {{ date('Y') }} GES Taller Automotriz • Portal de Consulta Pública</p>
    </div>

</div>
