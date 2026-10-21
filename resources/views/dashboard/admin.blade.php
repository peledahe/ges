<div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header con Resumen General -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 sm:p-8 shadow-sm border border-gray-200/80 dark:border-slate-800 relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 dark:bg-indigo-950/70 text-indigo-700 dark:text-indigo-300 border border-indigo-200/80 dark:border-indigo-800/60 mb-3">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Panel Administrativo General
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">Control Operativo y Financiero</h1>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mt-1 max-w-xl">
                        Supervisa en tiempo real la productividad del taller, flujo financiero, cuellos de botella y vehículos listos para entrega.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('workflow.index') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition shadow-md shadow-indigo-600/20">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                        </svg>
                        Tablero Kanban
                    </a>
                    <a href="{{ route('reportes.index') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl text-sm font-bold text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-950/60 hover:bg-indigo-100 dark:hover:bg-indigo-900/60 border border-indigo-200/80 dark:border-indigo-800/60 transition shadow-sm">
                        <svg class="w-4 h-4 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Reportes
                    </a>
                    <a href="{{ route('recepcion') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl text-sm font-bold text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 border border-gray-300 dark:border-gray-600 transition shadow-sm">
                        <svg class="w-4 h-4 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Nueva Recepción
                    </a>
                </div>
            </div>
        </div>

        <!-- KPIs Financieros y Operativos -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Órdenes Activas -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/60 transition hover:shadow-md">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold tracking-wider uppercase text-gray-500 dark:text-gray-400">En Taller</span>
                    <div class="p-2.5 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ $data['active_orders'] }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-2 flex items-center gap-1.5">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>{{ $data['orders_completed_today'] }} terminadas hoy</span>
                </div>
            </div>

            <!-- Cobrado este Mes -->
            @php
                $collectedBreakdown = \App\Helpers\CurrencyHelper::breakdown($data['total_collected_month']);
                $quotedBreakdown = \App\Helpers\CurrencyHelper::breakdown($data['total_active_quotations']);
                $pendingBreakdown = \App\Helpers\CurrencyHelper::breakdown($data['total_pending_balance']);
            @endphp
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/60 transition hover:shadow-md">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold tracking-wider uppercase text-gray-500 dark:text-gray-400">Ingresos del Mes</span>
                    <div class="p-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl sm:text-3xl font-extrabold text-emerald-600 dark:text-emerald-400">
                    {{ $collectedBreakdown['primary'] }}
                </div>
                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mt-2">
                    <span>Cobros confirmados</span>
                    <span class="font-bold text-gray-600 dark:text-gray-300 font-mono">{{ $collectedBreakdown['secondary'] }}</span>
                </div>
            </div>

            <!-- Total Cotizado Activo -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/60 transition hover:shadow-md">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold tracking-wider uppercase text-gray-500 dark:text-gray-400">Cotizado Activo</span>
                    <div class="p-2.5 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl sm:text-3xl font-extrabold text-indigo-600 dark:text-indigo-400">
                    {{ $quotedBreakdown['primary'] }}
                </div>
                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mt-2">
                    <span>Órdenes en proceso</span>
                    <span class="font-bold text-gray-600 dark:text-gray-300 font-mono">{{ $quotedBreakdown['secondary'] }}</span>
                </div>
            </div>

            <!-- Por Cobrar Pendiente -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/60 transition hover:shadow-md">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold tracking-wider uppercase text-gray-500 dark:text-gray-400">Saldo por Cobrar</span>
                    <div class="p-2.5 rounded-xl bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl sm:text-3xl font-extrabold text-amber-600 dark:text-amber-400">
                    {{ $pendingBreakdown['primary'] }}
                </div>
                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mt-2">
                    <span>Pendiente en órdenes</span>
                    <span class="font-bold text-gray-600 dark:text-gray-300 font-mono">{{ $pendingBreakdown['secondary'] }}</span>
                </div>
            </div>
        </div>

        <!-- Banner de Vehículos Listos para Entrega (Si existen) -->
        @if($data['ready_for_delivery'] > 0)
        <div class="bg-gradient-to-r from-emerald-500/10 via-teal-500/10 to-emerald-500/10 border border-emerald-500/30 rounded-2xl p-6 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 rounded-xl bg-emerald-500 text-white shadow-md shadow-emerald-500/30">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                            {{ $data['ready_for_delivery'] }} Vehículo(s) Listos para Entrega
                        </h2>
                        <p class="text-xs text-gray-600 dark:text-gray-400">
                            Han completado todos sus trabajos. Verifica el saldo antes de dar salida.
                        </p>
                    </div>
                </div>
                <a href="{{ route('workflow.index') }}" class="inline-flex items-center text-xs font-bold text-emerald-700 dark:text-emerald-400 hover:underline">
                    Ver todos en Tablero &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($data['ready_orders_list'] as $readyOrder)
                <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-emerald-200 dark:border-emerald-800/40 shadow-xs flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-mono font-bold text-sm px-2 py-0.5 rounded bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white">
                                {{ $readyOrder->vehicle->license_plate ?? 'S/P' }}
                            </span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $readyOrder->vehicle->brand ?? '' }} {{ $readyOrder->vehicle->model ?? '' }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-300 mt-1 truncate max-w-[200px]">
                            Cliente: {{ $readyOrder->vehicle->customer->name ?? $readyOrder->vehicle->owner_name ?? 'Particular' }}
                        </p>
                    </div>
                    <div class="text-right">
                        @if($readyOrder->pendingPaymentAmount() > 0)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-amber-100 dark:bg-amber-900/40 text-amber-800 dark:text-amber-300">
                                Debe Q{{ number_format($readyOrder->pendingPaymentAmount(), 2) }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-300">
                                100% Pagado
                            </span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Dos Columnas: Carga por Área & Cuellos de Botella -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Carga de Trabajo por Área -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/60">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        Distribución de Carga por Área
                    </h2>
                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">
                        {{ $data['active_orders'] }} en total
                    </span>
                </div>

                <div class="space-y-4">
                    @php
                        $maxOrders = max(1, $data['orders_by_area']->max('work_orders_count') ?? 1);
                    @endphp
                    @forelse($data['orders_by_area'] as $area)
                        @php
                            $percentage = min(100, round(($area->work_orders_count / $maxOrders) * 100));
                        @endphp
                        <div>
                            <div class="flex justify-between items-center text-xs font-semibold mb-1">
                                <span class="text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
                                    {{ $area->name }}
                                </span>
                                <span class="text-gray-900 dark:text-white font-bold">
                                    {{ $area->work_orders_count }} órdenes
                                </span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2.5 overflow-hidden">
                                <div class="bg-indigo-600 dark:bg-indigo-500 h-2.5 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-gray-500 dark:text-gray-400 py-4 text-center">No hay áreas configuradas en el taller.</p>
                    @endforelse
                </div>
            </div>

            <!-- Cuellos de Botella / Alertas de Demora -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/60">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Atención Prioritaria (>3 días en taller)
                    </h2>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded bg-amber-100 dark:bg-amber-900/40 text-amber-800 dark:text-amber-300">
                        {{ $data['bottleneck_orders']->count() }} demorados
                    </span>
                </div>

                <div class="space-y-3">
                    @forelse($data['bottleneck_orders'] as $order)
                        <div class="p-3 rounded-xl bg-amber-50/50 dark:bg-amber-950/20 border border-amber-200/60 dark:border-amber-800/40 flex items-center justify-between">
                            <div class="min-w-0 pr-3">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono font-bold text-xs px-2 py-0.5 rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700">
                                        {{ $order->vehicle->license_plate ?? 'S/P' }}
                                    </span>
                                    <span class="text-xs font-medium text-gray-700 dark:text-gray-300 truncate">
                                        {{ $order->vehicle->brand ?? '' }} {{ $order->vehicle->model ?? '' }}
                                    </span>
                                </div>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">
                                    Área: <strong class="text-gray-700 dark:text-gray-200">{{ $order->area->name ?? 'Sin asignar' }}</strong> &bull; Ingresó {{ $order->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <a href="{{ route('workflow.index') }}" class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-amber-600 hover:bg-amber-700 text-white transition whitespace-nowrap">
                                Inspeccionar
                            </a>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="w-10 h-10 text-emerald-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-xs text-gray-500 dark:text-gray-400">¡Excelente! No hay órdenes con demoras excesivas en el taller.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Tabla de Órdenes Recientes en el Taller -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/60 overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700/60 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Órdenes Activas Recientes</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Últimos vehículos en proceso de trabajo en el taller</p>
                </div>
                <a href="{{ route('workflow.index') }}" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">
                    Ver Tablero Completo &rarr;
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-gray-700/30 text-[11px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700/60">
                            <th class="px-6 py-3.5">Orden</th>
                            <th class="px-6 py-3.5">Vehículo & Placa</th>
                            <th class="px-6 py-3.5">Cliente</th>
                            <th class="px-6 py-3.5">Área Actual</th>
                            <th class="px-6 py-3.5">Estado</th>
                            <th class="px-6 py-3.5 text-right">Cotización</th>
                            <th class="px-6 py-3.5 text-right">Saldo</th>
                            <th class="px-6 py-3.5 text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/40 text-xs text-gray-700 dark:text-gray-300">
                        @forelse($data['recent_orders'] as $order)
                        <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-700/30 transition">
                            <td class="px-6 py-4 font-mono font-semibold text-gray-900 dark:text-white">
                                #{{ $order->id }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900 dark:text-white">
                                    {{ $order->vehicle->license_plate ?? 'S/P' }}
                                </div>
                                <div class="text-[11px] text-gray-500 dark:text-gray-400">
                                    {{ $order->vehicle->brand ?? '' }} {{ $order->vehicle->model ?? '' }} ({{ $order->vehicle->year ?? 'N/A' }})
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                {{ $order->vehicle->customer->name ?? $order->vehicle->owner_name ?? 'Particular' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-indigo-600 dark:text-indigo-400">
                                    {{ $order->area->name ?? 'Sin asignar' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    @if($order->status === 'recibido') bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                    @elseif($order->status === 'presupuesto') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-300
                                    @elseif($order->status === 'en_espera') bg-orange-100 text-orange-800 dark:bg-orange-900/40 dark:text-orange-300
                                    @elseif($order->status === 'trabajando') bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300
                                    @elseif($order->status === 'revision') bg-purple-100 text-purple-800 dark:bg-purple-900/40 dark:text-purple-300
                                    @elseif($order->status === 'terminado') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300
                                    @else bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-300
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-medium">
                                @if($order->total_amount)
                                    <div>@currency($order->total_amount)</div>
                                    <div class="text-[11px] text-gray-400 font-mono">
                                        @if(\App\Helpers\CurrencyHelper::getActiveCurrency() === 'USD')
                                            Q{{ number_format($order->total_amount, 2) }}
                                        @else
                                            US$ {{ number_format(\App\Helpers\CurrencyHelper::convertFromGtq($order->total_amount, 'USD'), 2) }}
                                        @endif
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400 italic">Sin cotizar</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right font-bold">
                                @if($order->pendingPaymentAmount() > 0)
                                    <span class="text-amber-600 dark:text-amber-400">
                                        @currency($order->pendingPaymentAmount())
                                    </span>
                                @else
                                    <span class="text-emerald-600 dark:text-emerald-400">Pagado</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('workflow.index') }}" class="p-1.5 rounded-lg text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition inline-flex items-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-8 text-xs text-gray-500 dark:text-gray-400">
                                No hay órdenes de trabajo activas actualmente.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Accesos Rápidos de Administración -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <a href="{{ route('users.index') }}" class="p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700/60 shadow-xs hover:border-indigo-500 dark:hover:border-indigo-500 transition flex items-center gap-3 group">
                <div class="p-2.5 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 group-hover:scale-105 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">Usuarios</h3>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400">Roles y permisos</p>
                </div>
            </a>

            <a href="{{ route('configuracion') }}" class="p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700/60 shadow-xs hover:border-indigo-500 dark:hover:border-indigo-500 transition flex items-center gap-3 group">
                <div class="p-2.5 rounded-xl bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 group-hover:scale-105 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">Áreas & Taller</h3>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400">Flujo operativo</p>
                </div>
            </a>

            <a href="{{ route('workflow.index') }}" class="p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700/60 shadow-xs hover:border-indigo-500 dark:hover:border-indigo-500 transition flex items-center gap-3 group">
                <div class="p-2.5 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 group-hover:scale-105 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">Tablero</h3>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400">Control visual</p>
                </div>
            </a>

            <a href="{{ route('rastreo') }}" class="p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700/60 shadow-xs hover:border-indigo-500 dark:hover:border-indigo-500 transition flex items-center gap-3 group">
                <div class="p-2.5 rounded-xl bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 group-hover:scale-105 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">Rastreo Público</h3>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400">Consulta por placa</p>
                </div>
            </a>
        </div>

    </div>
</div>
