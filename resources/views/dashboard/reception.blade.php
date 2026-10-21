<div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header con Acciones de Recepción -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 sm:p-8 shadow-sm border border-gray-200/80 dark:border-slate-800 relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-950/70 text-emerald-700 dark:text-emerald-300 border border-emerald-200/80 dark:border-emerald-800/60 mb-3">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Recepción & Entrega de Vehículos
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">Módulo de Atención y Salidas</h1>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mt-1 max-w-xl">
                        Registra el ingreso de nuevos clientes, revisa el estatus de entrega y verifica los pagos antes de entregar las llaves.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('recepcion') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition shadow-md shadow-emerald-600/20">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Nueva Recepción
                    </a>
                    <a href="{{ route('workflow.index') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl text-sm font-bold text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 border border-gray-300 dark:border-gray-600 transition shadow-sm">
                        <svg class="w-4 h-4 mr-2 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                        </svg>
                        Ver Tablero
                    </a>
                </div>
            </div>
        </div>

        <!-- KPIs de Recepción & Entrega -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Ingresos Hoy -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/60 transition hover:shadow-md">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold tracking-wider uppercase text-gray-500 dark:text-gray-400">Ingresados Hoy</span>
                    <div class="p-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-emerald-600 dark:text-emerald-400">{{ $data['orders_today'] }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    Recepciones completadas hoy
                </div>
            </div>

            <!-- Ingresos Esta Semana -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/60 transition hover:shadow-md">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold tracking-wider uppercase text-gray-500 dark:text-gray-400">Esta Semana</span>
                    <div class="p-2.5 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-blue-600 dark:text-blue-400">{{ $data['orders_week'] }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    Total de la semana en curso
                </div>
            </div>

            <!-- Listos para Entrega -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/60 transition hover:shadow-md">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold tracking-wider uppercase text-gray-500 dark:text-gray-400">Listos para Entrega</span>
                    <div class="p-2.5 rounded-xl bg-teal-50 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-teal-600 dark:text-teal-400">{{ $data['ready_for_delivery_count'] }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    Trabajos terminados por entregar
                </div>
            </div>

            <!-- Saldo Pendiente en Entrega -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/60 transition hover:shadow-md">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold tracking-wider uppercase text-gray-500 dark:text-gray-400">Por Cobrar en Entrega</span>
                    <div class="p-2.5 rounded-xl bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-amber-600 dark:text-amber-400">{{ $data['ready_with_pending_payment'] }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    Vehículos listos con saldo por cobrar
                </div>
            </div>
        </div>

        <!-- Sección Clave: Vehículos Listos para Entrega -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/60 overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700/60 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        Vehículos Terminados (Listos para Entrega al Cliente)
                    </h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                        Verifica el saldo pendiente antes de entregar el vehículo al cliente.
                    </p>
                </div>
                <a href="{{ route('workflow.index') }}" class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:underline">
                    Ver en Tablero &rarr;
                </a>
            </div>

            <div class="divide-y divide-gray-100 dark:divide-gray-700/40">
                @forelse($data['ready_orders'] as $order)
                <div class="p-6 hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="space-y-1.5">
                        <div class="flex items-center gap-2">
                            <span class="font-mono font-bold text-sm px-2.5 py-1 rounded bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-600">
                                {{ $order->vehicle->license_plate ?? 'S/P' }}
                            </span>
                            <span class="text-sm font-bold text-gray-900 dark:text-white">
                                {{ $order->vehicle->brand ?? '' }} {{ $order->vehicle->model ?? '' }} ({{ $order->vehicle->year ?? 'N/A' }})
                            </span>
                            <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                                Terminado
                            </span>
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-300">
                            <strong>Cliente:</strong> {{ $order->vehicle->customer->name ?? $order->vehicle->owner_name ?? 'Particular' }}
                            @php
                                $phone = $order->vehicle->customer->phone ?? $order->vehicle->owner_phone ?? null;
                            @endphp
                            @if($phone)
                                &bull; <strong>Tel:</strong> {{ $phone }}
                            @endif
                        </p>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <div class="text-xs text-gray-500 dark:text-gray-400">Total Cotizado: @currencyDual($order->total_amount ?? 0)</div>
                            @if($order->pendingPaymentAmount() > 0)
                                <div class="text-sm font-extrabold text-amber-600 dark:text-amber-400">
                                    Cobrar: @currency($order->pendingPaymentAmount())
                                </div>
                            @else
                                <div class="text-sm font-extrabold text-emerald-600 dark:text-emerald-400">
                                    Totalmente Pagado
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center gap-2">
                            @if(!empty($phone))
                            @php
                                $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
                                $waMsg = urlencode("¡Hola! Te informamos que tu vehículo " . ($order->vehicle->brand ?? '') . " con placas " . ($order->vehicle->license_plate ?? '') . " ya está listo para entrega en el taller.");
                            @endphp
                            <a href="https://wa.me/{{ $cleanPhone }}?text={{ $waMsg }}" target="_blank" class="p-2 rounded-xl text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 transition" title="Avisar por WhatsApp">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                                </svg>
                            </a>
                            @endif

                            <a href="{{ route('workflow.index') }}" class="px-3.5 py-2 rounded-xl text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-500 transition shadow-sm">
                                Ver en Tablero
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-10 text-xs text-gray-500 dark:text-gray-400">
                    No hay vehículos esperando entrega en este momento.
                </div>
                @endforelse
            </div>
        </div>

        <!-- Tabla de Últimas Recepciones -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/60 overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700/60 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Últimas Recepciones Realizadas</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Historial reciente de ingresos de vehículos al taller</p>
                </div>
                <a href="{{ route('workflow.index') }}" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">
                    Ver todas &rarr;
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
                            <th class="px-6 py-3.5">Fecha Ingreso</th>
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
                                <span class="font-bold text-gray-900 dark:text-white">{{ $order->vehicle->license_plate ?? 'S/P' }}</span>
                                <span class="text-gray-500 dark:text-gray-400 block text-[11px]">{{ $order->vehicle->brand ?? '' }} {{ $order->vehicle->model ?? '' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                {{ $order->vehicle->customer->name ?? $order->vehicle->owner_name ?? 'Particular' }}
                            </td>
                            <td class="px-6 py-4 font-semibold text-indigo-600 dark:text-indigo-400">
                                {{ $order->area->name ?? 'Sin asignar' }}
                            </td>
                            <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                                {{ $order->created_at->format('d/m/Y H:i') }}
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
                            <td colspan="6" class="text-center py-8 text-xs text-gray-500 dark:text-gray-400">
                                No hay recepciones registradas todavía.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
