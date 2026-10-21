<div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header del Encargado / Mecánico -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 sm:p-8 shadow-sm border border-gray-200/80 dark:border-slate-800 relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 dark:bg-blue-950/70 text-blue-700 dark:text-blue-300 border border-blue-200/80 dark:border-blue-800/60 mb-3">
                        <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                        Estación Operativa de Trabajo
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">Panel de Taller & Mecánica</h1>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mt-1 max-w-xl">
                        Supervisa tus tareas activas, vehículos en proceso de reparación y control de calidad en tus áreas asignadas.
                    </p>

                    <!-- Áreas Asignadas -->
                    @if($data['has_areas'] && $data['assigned_areas']->isNotEmpty())
                    <div class="flex flex-wrap items-center gap-2 mt-4">
                        <span class="text-xs text-gray-500 dark:text-gray-400 font-semibold">Tus Áreas:</span>
                        @foreach($data['assigned_areas'] as $area)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 dark:bg-blue-900/40 text-blue-800 dark:text-blue-200 border border-blue-200 dark:border-blue-700/50">
                                {{ $area->name }}
                            </span>
                        @endforeach
                    </div>
                    @endif
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('workflow.index') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 transition shadow-md shadow-blue-600/20">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                        </svg>
                        Mover en Tablero
                    </a>
                </div>
            </div>
        </div>

        @if(!$data['has_areas'])
        <!-- Aviso sin áreas asignadas -->
        <div class="bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/60 rounded-2xl p-8 text-center">
            <svg class="w-12 h-12 text-amber-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Sin Áreas Asignadas</h2>
            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1 max-w-md mx-auto">
                No tienes áreas de trabajo asignadas a tu cuenta actualmente. Solicita al administrador del taller que configure tus áreas en el panel de usuarios.
            </p>
        </div>
        @else

        <!-- KPIs Operativos del Área -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Total en mis Áreas -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/60 transition hover:shadow-md">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold tracking-wider uppercase text-gray-500 dark:text-gray-400">Total en mis Áreas</span>
                    <div class="p-2.5 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ $data['orders_in_area'] }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    Vehículos presentes en tus estaciones
                </div>
            </div>

            <!-- Trabajando / En Proceso -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/60 transition hover:shadow-md">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold tracking-wider uppercase text-gray-500 dark:text-gray-400">En Reparación Activa</span>
                    <div class="p-2.5 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-blue-600 dark:text-blue-400">{{ $data['orders_working'] }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    Mecánicos trabajando ahora mismo
                </div>
            </div>

            <!-- En Revisión / Calidad -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/60 transition hover:shadow-md">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold tracking-wider uppercase text-gray-500 dark:text-gray-400">Control de Calidad</span>
                    <div class="p-2.5 rounded-xl bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-purple-600 dark:text-purple-400">{{ $data['orders_review'] }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    En fase de prueba o inspección
                </div>
            </div>

            <!-- En Espera -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/60 transition hover:shadow-md">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold tracking-wider uppercase text-gray-500 dark:text-gray-400">En Espera</span>
                    <div class="p-2.5 rounded-xl bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-amber-600 dark:text-amber-400">{{ $data['orders_waiting'] }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    Por piezas o autorización de cliente
                </div>
            </div>
        </div>

        <!-- Lista de Vehículos Asignados con Detalle Técnico -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/60 overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700/60 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Vehículos en mis Estaciones</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Órdenes asignadas a tus áreas con motivo y estado</p>
                </div>
                <a href="{{ route('workflow.index') }}" class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:underline">
                    Abrir Tablero Kanban &rarr;
                </a>
            </div>

            <div class="divide-y divide-gray-100 dark:divide-gray-700/40">
                @forelse($data['active_orders'] as $order)
                <div class="p-6 hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div class="space-y-2 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="font-mono font-bold text-sm px-2.5 py-1 rounded bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-600">
                                {{ $order->vehicle->license_plate ?? 'S/P' }}
                            </span>
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white">
                                {{ $order->vehicle->brand ?? '' }} {{ $order->vehicle->model ?? '' }} ({{ $order->vehicle->year ?? 'N/A' }})
                            </h3>
                            <span class="text-xs px-2 py-0.5 rounded-full font-semibold bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300">
                                {{ $order->area->name ?? 'Sin asignar' }}
                            </span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                @if($order->status === 'trabajando') bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300
                                @elseif($order->status === 'revision') bg-purple-100 text-purple-800 dark:bg-purple-900/40 dark:text-purple-300
                                @elseif($order->status === 'en_espera') bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300
                                @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                            </span>
                        </div>

                        <!-- Trabajo Solicitado / Motivo -->
                        <p class="text-xs text-gray-600 dark:text-gray-300 line-clamp-2">
                            <strong>Trabajo:</strong> {{ $order->customer_notes ?? 'Revisión y mantenimiento general.' }}
                        </p>

                        <!-- Datos Adicionales (Cliente si tiene permiso, anomalías de checklist) -->
                        <div class="flex flex-wrap items-center gap-4 text-[11px] text-gray-500 dark:text-gray-400 pt-1">
                            @if($data['can_view_contact_info'])
                                <span>Cliente: <strong class="text-gray-700 dark:text-gray-300">{{ $order->vehicle->customer->name ?? $order->vehicle->owner_name ?? 'Particular' }}</strong></span>
                                @if(!empty($order->vehicle->customer->phone ?? $order->vehicle->owner_phone))
                                    <span>Tel: {{ $order->vehicle->customer->phone ?? $order->vehicle->owner_phone }}</span>
                                @endif
                            @endif
                            <span>Ingresó: {{ $order->created_at->format('d/m/Y H:i') }} ({{ $order->created_at->diffForHumans() }})</span>
                            @if($order->checklists->isNotEmpty())
                                <span class="inline-flex items-center text-indigo-600 dark:text-indigo-400 font-semibold">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    Checklist registrado
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-2 self-end lg:self-center">
                        <a href="{{ route('workflow.index') }}" class="inline-flex items-center px-3.5 py-2 rounded-xl text-xs font-semibold text-white bg-blue-600 hover:bg-blue-500 transition shadow-sm">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Ver en Tablero
                        </a>
                    </div>
                </div>
                @empty
                <div class="text-center py-12">
                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">Sin trabajo pendiente</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Actualmente no hay vehículos en tus estaciones asignadas.</p>
                </div>
                @endforelse
            </div>
        </div>
        @endif

    </div>
</div>
