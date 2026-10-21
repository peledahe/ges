<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

        <!-- HERO / BIENVENIDA -->
        <div class="bg-gradient-to-r from-indigo-700 via-indigo-800 to-blue-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden border border-indigo-500/20">
            <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-xs font-bold uppercase tracking-wider text-indigo-200 mb-3">
                        <span>👤</span>
                        <span>Portal del Cliente</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black tracking-tight">
                        Hola, {{ $data['user']->name }}
                    </h2>
                    <p class="text-indigo-200 text-sm mt-1 max-w-xl">
                        Consulta en tiempo real el estado de tus vehículos, cotizaciones autorizadas, abonos realizados y saldos pendientes.
                    </p>
                </div>

                @if ($data['tenant'])
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/15 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-white text-indigo-700 flex items-center justify-center font-black text-xl shadow-md">
                            @if ($data['tenant']->logo_path)
                                <img src="{{ Storage::url($data['tenant']->logo_path) }}" class="w-full h-full object-cover rounded-xl" alt="Logo">
                            @else
                                🏢
                            @endif
                        </div>
                        <div>
                            <span class="text-xs uppercase font-bold text-indigo-200 block">Tu Taller de Confianza</span>
                            <span class="font-bold text-sm block">{{ $data['tenant']->name }}</span>
                            @if ($data['tenant']->phone)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $data['tenant']->phone) }}" target="_blank" class="inline-flex items-center gap-1 text-xs text-emerald-300 hover:text-white font-bold mt-1 transition">
                                    <span>💬</span>
                                    <span>Contactar: {{ $data['tenant']->phone }}</span>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- TARJETAS DE ESTADÍSTICAS FINANCIERAS Y OPERATIVAS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <!-- Vehículos en Reparación -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400 block">En Taller Ahora</span>
                    <span class="text-2xl font-black text-gray-900 dark:text-white mt-1 block">
                        {{ $data['active_orders_count'] }}
                    </span>
                    <span class="text-xs text-indigo-600 dark:text-indigo-400 font-semibold">
                        {{ $data['active_orders_count'] === 1 ? 'Vehículo en proceso' : 'Vehículos en proceso' }}
                    </span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xl">
                    🚗
                </div>
            </div>

            <!-- Total Presupuestado / Cotizado -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400 block">Total Cotizado</span>
                    <span class="text-2xl font-black text-gray-900 dark:text-white mt-1 block">
                        Q{{ number_format($data['total_quotations'], 2) }}
                    </span>
                    <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Presupuesto acumulado</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xl">
                    💰
                </div>
            </div>

            <!-- Total Pagado / Abonado -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400 block">Total Abonado</span>
                    <span class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1 block">
                        Q{{ number_format($data['total_paid'], 2) }}
                    </span>
                    <span class="text-xs text-emerald-600 dark:text-emerald-400 font-bold">Pagos registrados</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl">
                    💳
                </div>
            </div>

            <!-- Saldo Pendiente -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400 block">Saldo Pendiente</span>
                    <span class="text-2xl font-black {{ $data['pending_payment'] > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-gray-900 dark:text-white' }} mt-1 block">
                        Q{{ number_format($data['pending_payment'], 2) }}
                    </span>
                    @if ($data['pending_payment'] > 0)
                        <span class="text-xs text-rose-600 dark:text-rose-400 font-bold">Por cancelar</span>
                    @else
                        <span class="text-xs text-emerald-600 dark:text-emerald-400 font-bold">✓ Al día</span>
                    @endif
                </div>
                <div class="w-12 h-12 rounded-2xl {{ $data['pending_payment'] > 0 ? 'bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-500' }} flex items-center justify-center text-xl">
                    ⏳
                </div>
            </div>
        </div>

        <!-- SECCIÓN: TRABAJOS Y REPARACIONES EN CURSO -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-black text-gray-900 dark:text-white flex items-center gap-2">
                        <span>🔧</span>
                        <span>Trabajos y Vehículos en Proceso</span>
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Seguimiento en vivo de las etapas y avances en el taller.
                    </p>
                </div>

                <a href="{{ route('rastreo') }}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1">
                    <span>🔍</span>
                    <span>Consultar por Placa</span>
                </a>
            </div>

            @forelse ($data['active_orders'] as $order)
                @php
                    $stages = [
                        'recibido' => ['label' => 'Recepción', 'step' => 1],
                        'presupuesto' => ['label' => 'Presupuesto', 'step' => 2],
                        'en_espera' => ['label' => 'En Espera', 'step' => 3],
                        'trabajando' => ['label' => 'En Proceso', 'step' => 4],
                        'revision' => ['label' => 'Control de Calidad', 'step' => 5],
                        'terminado' => ['label' => 'Listo para Entrega', 'step' => 6],
                    ];
                    $currentStep = $stages[$order->status]['step'] ?? 1;
                @endphp

                <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 space-y-6">
                    
                    <!-- Ficha Superior: Vehículo y Estado -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-gray-100 dark:border-gray-700/60">
                        <div class="flex items-center gap-3">
                            <span class="text-base font-mono font-black tracking-wider bg-amber-300 text-gray-900 px-3 py-1 rounded-md border-2 border-gray-900 shadow-sm">
                                {{ $order->vehicle->plate ?? 'S/P' }}
                            </span>
                            <div>
                                <h4 class="font-black text-base text-gray-900 dark:text-white">
                                    {{ $order->vehicle->brand }} {{ $order->vehicle->model }}
                                    <span class="font-normal text-xs text-gray-400">({{ $order->vehicle->year ?? 'N/D' }})</span>
                                </h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                    Color: {{ $order->vehicle->color ?? 'N/D' }} • 
                                    @if (($order->vehicle->fuel_type ?? '') === 'electrico')
                                        ⚡ Eléctrico
                                    @elseif (($order->vehicle->fuel_type ?? '') === 'diesel')
                                        🛢️ Diésel
                                    @else
                                        ⛽ Gasolina
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            @if ($order->area)
                                <span class="text-xs font-bold px-3 py-1 rounded-full bg-purple-50 text-purple-700 dark:bg-purple-950/50 dark:text-purple-300 border border-purple-200 dark:border-purple-800">
                                    📍 Área: {{ $order->area->name }}
                                </span>
                            @endif
                            <span class="text-xs font-black px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 dark:bg-indigo-950/50 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                            </span>
                        </div>
                    </div>

                    <!-- BARRA DE PROGRESO DE ETAPAS -->
                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-3">Progreso de Reparación</span>
                        <div class="grid grid-cols-2 sm:grid-cols-6 gap-2">
                            @foreach ($stages as $stKey => $stData)
                                @php
                                    $isPast = $currentStep >= $stData['step'];
                                    $isCurrent = $order->status === $stKey;
                                @endphp
                                <div class="p-2.5 rounded-xl border text-center transition {{ $isCurrent ? 'bg-indigo-600 text-white border-indigo-600 shadow-md ring-2 ring-indigo-300 dark:ring-indigo-800' : ($isPast ? 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 border-indigo-200 dark:border-indigo-800' : 'bg-gray-50 dark:bg-gray-900/40 text-gray-400 border-gray-200 dark:border-gray-800') }}">
                                    <span class="text-[10px] font-mono block opacity-80">Paso {{ $stData['step'] }}</span>
                                    <span class="text-xs font-bold block truncate mt-0.5">{{ $stData['label'] }}</span>
                                    @if ($isCurrent)
                                        <span class="inline-block w-1.5 h-1.5 rounded-full bg-white mt-1 animate-ping"></span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- GRID: TRABAJO SOLICITADO & FINANZAS DEL TRABAJO -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        
                        <!-- Trabajo Solicitado -->
                        <div class="p-4 bg-gray-50 dark:bg-gray-900/60 rounded-2xl border border-gray-200 dark:border-gray-700">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-2 flex items-center gap-1.5">
                                <span>🛠️</span>
                                <span>Trabajo Solicitado / Reparación</span>
                            </span>
                            <p class="text-xs text-gray-800 dark:text-gray-200 whitespace-pre-line leading-relaxed">
                                {{ $order->notes ?: 'Diagnóstico y servicio general según hoja de recepción.' }}
                            </p>

                            <!-- Checklist / Novedades -->
                            @if ($order->checklists && $order->checklists->where('status', '!=', 'correct')->isNotEmpty())
                                <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700/60">
                                    <span class="text-[11px] font-bold text-amber-700 dark:text-amber-400 block mb-1 flex items-center gap-1">
                                        <span>⚠️</span>
                                        <span>Novedades detectadas en recepción:</span>
                                    </span>
                                    <div class="space-y-1 text-xs">
                                        @foreach ($order->checklists->where('status', '!=', 'correct') as $item)
                                            <div class="flex items-center justify-between text-[11px] text-gray-600 dark:text-gray-400">
                                                <span>• {{ $item->group_name }}: {{ $item->item_name }}</span>
                                                <span class="uppercase text-[10px] font-bold px-1.5 py-0.2 rounded bg-rose-50 text-rose-700 dark:bg-rose-950/50 dark:text-rose-300">
                                                    {{ $item->status }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Finanzas del Trabajo: Cotización y Pagos -->
                        <div class="p-4 bg-gray-50 dark:bg-gray-900/60 rounded-2xl border border-gray-200 dark:border-gray-700 flex flex-col justify-between">
                            <div>
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-3 flex items-center gap-1.5">
                                    <span>💰</span>
                                    <span>Cotización y Estado Financiero</span>
                                </span>

                                <div class="space-y-2 text-xs">
                                    <div class="flex items-center justify-between p-2.5 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                                        <span class="text-gray-500 dark:text-gray-400 font-bold">Total Cotizado:</span>
                                        <span class="text-sm font-black text-gray-900 dark:text-white">
                                            {{ $order->total_amount ? \App\Helpers\CurrencyHelper::formatDual($order->total_amount) : 'En formulación técnica' }}
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between p-2.5 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                                        <span class="text-gray-500 dark:text-gray-400 font-bold">Abonado / Pagado:</span>
                                        <span class="text-sm font-black text-emerald-600 dark:text-emerald-400">
                                            @currencyDual($order->paid_amount ?? 0)
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between p-2.5 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                                        <span class="text-gray-500 dark:text-gray-400 font-bold">Saldo Pendiente:</span>
                                        <span class="text-sm font-black {{ $order->pendingPaymentAmount() > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600' }}">
                                            @if($order->pendingPaymentAmount() > 0)
                                                @currencyDual($order->pendingPaymentAmount())
                                            @else
                                                ✓ Solvente
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700/60 flex items-center justify-between text-xs">
                                <span class="text-gray-500 dark:text-gray-400 font-semibold">Estado de Pago:</span>
                                @if ($order->payment_status === 'pagado')
                                    <span class="font-black text-emerald-700 bg-emerald-100 dark:bg-emerald-950/60 dark:text-emerald-300 px-2.5 py-0.5 rounded-full border border-emerald-300">
                                        ✓ Liquidado / Pagado
                                    </span>
                                @elseif ($order->payment_status === 'parcial')
                                    <span class="font-black text-amber-700 bg-amber-100 dark:bg-amber-950/60 dark:text-amber-300 px-2.5 py-0.5 rounded-full border border-amber-300">
                                        🟡 Pago Parcial
                                    </span>
                                @else
                                    <span class="font-bold text-gray-500 bg-gray-200 dark:bg-gray-700 px-2.5 py-0.5 rounded-full">
                                        💳 Pendiente
                                    </span>
                                @endif
                            </div>
                        </div>

                    </div>

                </div>
            @empty
                <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-200 dark:border-gray-700 p-8 text-center text-gray-500 dark:text-gray-400">
                    <span class="text-4xl block mb-2">🚗</span>
                    <p class="font-bold text-base text-gray-800 dark:text-gray-200">No tienes vehículos activos en el taller en este momento.</p>
                    <p class="text-xs mt-1 text-gray-400">Cuando ingreses un vehículo para mantenimiento o reparación, podrás seguir todo su avance aquí.</p>
                </div>
            @endforelse
        </div>

        <!-- SECCIÓN: MIS VEHÍCULOS REGISTRADOS -->
        @if ($data['vehicles']->isNotEmpty())
            <div class="space-y-4">
                <h3 class="text-lg font-black text-gray-900 dark:text-white flex items-center gap-2">
                    <span>📋</span>
                    <span>Mis Vehículos Registrados ({{ $data['vehicles']->count() }})</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($data['vehicles'] as $v)
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-mono font-black tracking-wider bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white px-2.5 py-1 rounded border border-gray-300 dark:border-gray-600">
                                    {{ $v->plate }}
                                </span>
                                <div>
                                    <span class="font-bold text-sm text-gray-900 dark:text-white block">
                                        {{ $v->brand }} {{ $v->model }}
                                    </span>
                                    <span class="text-[11px] text-gray-400 block">
                                        {{ $v->year ?? 'Año N/D' }} • {{ $v->color ?? 'Color N/D' }}
                                    </span>
                                </div>
                            </div>

                            <a href="{{ route('rastreo', ['plate' => $v->plate]) }}" class="p-2 text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 rounded-xl transition" title="Consultar Rápido">
                                🔍
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- SECCIÓN: HISTORIAL DE TRABAJOS ENTREGADOS -->
        @if ($data['history_orders']->isNotEmpty())
            <div class="space-y-4">
                <h3 class="text-lg font-black text-gray-900 dark:text-white flex items-center gap-2">
                    <span>📦</span>
                    <span>Historial de Reparaciones Entregadas</span>
                </h3>

                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-gray-50 dark:bg-gray-900/60 text-gray-400 font-bold uppercase text-[10px] border-b border-gray-200 dark:border-gray-700">
                                <tr>
                                    <th class="p-3">Vehículo</th>
                                    <th class="p-3">Fecha de Entrega</th>
                                    <th class="p-3">Trabajo Realizado</th>
                                    <th class="p-3">Total Pagado</th>
                                    <th class="p-3 text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60">
                                @foreach ($data['history_orders'] as $hOrder)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                        <td class="p-3 font-bold text-gray-900 dark:text-white">
                                            <span class="font-mono bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded text-[11px] mr-1">
                                                {{ $hOrder->vehicle?->plate }}
                                            </span>
                                            {{ $hOrder->vehicle?->brand }} {{ $hOrder->vehicle?->model }}
                                        </td>
                                        <td class="p-3 text-gray-500 dark:text-gray-400 font-mono text-[11px]">
                                            {{ $hOrder->delivered_at ? $hOrder->delivered_at->format('d/m/Y') : $hOrder->updated_at->format('d/m/Y') }}
                                        </td>
                                        <td class="p-3 text-gray-700 dark:text-gray-300 max-w-xs truncate">
                                            {{ $hOrder->notes ?: 'Mantenimiento / Reparación completada' }}
                                        </td>
                                        <td class="p-3 font-mono font-bold text-emerald-600 dark:text-emerald-400">
                                            @currencyDual($hOrder->total_amount ?? $hOrder->paid_amount ?? 0)
                                        </td>
                                        <td class="p-3 text-center">
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300">
                                                ✓ Entregado
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
