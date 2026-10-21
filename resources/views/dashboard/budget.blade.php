<div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header de Presupuestos -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 sm:p-8 shadow-sm border border-gray-200/80 dark:border-slate-800 relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 dark:bg-amber-950/70 text-amber-800 dark:text-amber-300 border border-amber-200/80 dark:border-amber-800/60 mb-3">
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                        Diagnóstico & Estimación de Costos
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">Panel de Presupuestos y Cotizaciones</h1>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mt-1 max-w-xl">
                        Formula y da seguimiento a los presupuestos de mano de obra y repuestos pendientes de autorización.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('workflow.index') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-amber-600 hover:bg-amber-700 transition shadow-md shadow-amber-600/20">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                        </svg>
                        Tablero de Diagnóstico
                    </a>
                </div>
            </div>
        </div>

        <!-- KPIs de Presupuestos -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Pendientes de Cotizar -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/60 transition hover:shadow-md">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold tracking-wider uppercase text-gray-500 dark:text-gray-400">Por Cotizar</span>
                    <div class="p-2.5 rounded-xl bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-amber-600 dark:text-amber-400">{{ $data['pending_budget'] }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    En espera de desglose o precios
                </div>
            </div>

            <!-- Aprobados Hoy -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/60 transition hover:shadow-md">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold tracking-wider uppercase text-gray-500 dark:text-gray-400">Aprobados Hoy</span>
                    <div class="p-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-emerald-600 dark:text-emerald-400">{{ $data['budgets_approved_today'] }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    Pasaron a reparación activa
                </div>
            </div>

            <!-- Aprobados Esta Semana -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/60 transition hover:shadow-md">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold tracking-wider uppercase text-gray-500 dark:text-gray-400">Esta Semana</span>
                    <div class="p-2.5 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-blue-600 dark:text-blue-400">{{ $data['budgets_approved_week'] }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    Total autorizados esta semana
                </div>
            </div>

            <!-- Total Presupuestado en el Mes -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/60 transition hover:shadow-md">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold tracking-wider uppercase text-gray-500 dark:text-gray-400">Monto Presupuestado</span>
                    <div class="p-2.5 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-indigo-600 dark:text-indigo-400">
                    Q{{ number_format($data['total_budgeted_month'], 2) }}
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    Monto total cotizado en el mes
                </div>
            </div>
        </div>

        <!-- Cola Prioritaria: Órdenes esperando Presupuesto -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/60 overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700/60 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                        Cola de Vehículos en Diagnóstico / Presupuesto
                    </h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                        Vehículos esperando cotización de repuestos o mano de obra.
                    </p>
                </div>
                <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-amber-100 dark:bg-amber-900/40 text-amber-800 dark:text-amber-300">
                    {{ $data['orders_needing_budget']->count() }} pendientes
                </span>
            </div>

            <div class="divide-y divide-gray-100 dark:divide-gray-700/40">
                @forelse($data['orders_needing_budget'] as $order)
                <div class="p-6 hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="space-y-1.5 flex-1">
                        <div class="flex items-center gap-2">
                            <span class="font-mono font-bold text-sm px-2.5 py-1 rounded bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-600">
                                {{ $order->vehicle->license_plate ?? 'S/P' }}
                            </span>
                            <span class="text-sm font-bold text-gray-900 dark:text-white">
                                {{ $order->vehicle->brand ?? '' }} {{ $order->vehicle->model ?? '' }} ({{ $order->vehicle->year ?? 'N/A' }})
                            </span>
                            <span class="text-xs px-2 py-0.5 rounded-full font-semibold bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300">
                                {{ $order->area->name ?? 'Sin asignar' }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-300">
                            <strong>Diagnóstico / Motivo:</strong> {{ $order->customer_notes ?? 'Revisión y presupuesto general solicitado por el cliente.' }}
                        </p>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400">
                            Cliente: <strong class="text-gray-700 dark:text-gray-300">{{ $order->vehicle->customer->name ?? $order->vehicle->owner_name ?? 'Particular' }}</strong>
                            &bull; Ingresó: {{ $order->created_at->diffForHumans() }} ({{ $order->created_at->format('d/m H:i') }})
                        </p>
                    </div>

                    <div class="flex items-center gap-3 self-end md:self-center">
                        <div class="text-right">
                            <div class="text-xs text-gray-500 dark:text-gray-400">Monto actual</div>
                            <div class="text-sm font-extrabold text-gray-900 dark:text-white">
                                @if($order->total_amount)
                                    @currencyDual($order->total_amount)
                                @else
                                    <span class="text-xs text-amber-600 dark:text-amber-400 font-semibold">Pendiente</span>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('workflow.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-amber-600 hover:bg-amber-500 transition shadow-sm">
                            Cotizar en Tablero
                        </a>
                    </div>
                </div>
                @empty
                <div class="text-center py-10 text-xs text-gray-500 dark:text-gray-400">
                    ¡Al día! No hay órdenes pendientes de cotización.
                </div>
                @endforelse
            </div>
        </div>

        <!-- Cotizaciones Recientes Autorizadas -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/60 overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700/60 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Cotizaciones Autorizadas Recientes</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Órdenes presupuestadas que ya están en proceso</p>
                </div>
                <a href="{{ route('workflow.index') }}" class="text-xs font-semibold text-amber-600 dark:text-amber-400 hover:underline">
                    Ver Tablero &rarr;
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-gray-700/30 text-[11px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700/60">
                            <th class="px-6 py-3.5">Orden</th>
                            <th class="px-6 py-3.5">Placa & Vehículo</th>
                            <th class="px-6 py-3.5">Cliente</th>
                            <th class="px-6 py-3.5">Área Actual</th>
                            <th class="px-6 py-3.5 text-right">Monto Cotizado</th>
                            <th class="px-6 py-3.5 text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/40 text-xs text-gray-700 dark:text-gray-300">
                        @forelse($data['recent_quoted_orders'] as $order)
                        <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-700/30 transition">
                            <td class="px-6 py-4 font-mono font-semibold text-gray-900 dark:text-white">
                                #{{ $order->id }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-gray-900 dark:text-white">{{ $order->vehicle->license_plate ?? 'S/P' }}</span>
                                <span class="text-gray-500 dark:text-gray-400 block text-[11px]">{{ $order->vehicle->brand ?? '' }} {{ $order->vehicle->model ?? '' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                {{ $order->vehicle->customer->name ?? $order->vehicle->owner_name ?? 'Particular' }}
                            </td>
                            <td class="px-6 py-4 font-semibold text-indigo-600 dark:text-indigo-400">
                                {{ $order->area->name ?? 'Sin asignar' }}
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-emerald-600 dark:text-emerald-400">
                                @currencyDual($order->total_amount ?? 0)
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('workflow.index') }}" class="p-1.5 rounded-lg text-gray-500 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/30 transition inline-flex items-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-xs text-gray-500 dark:text-gray-400">
                                No hay cotizaciones autorizadas recientes.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
