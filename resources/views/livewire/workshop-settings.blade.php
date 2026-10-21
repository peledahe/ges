<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-extrabold text-2xl text-gray-900 dark:text-white leading-tight flex items-center gap-2.5">
                    <span class="p-1.5 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </span>
                    <span>{{ __('Configuración del Taller') }}</span>
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Gestiona las áreas operativas, flujo de trabajo del tablero Kanban y la información general del taller.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- BARRA DE PESTAÑAS (TABS) DENTRO DE LIVEWIRE -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-2 shadow-sm border border-gray-200/80 dark:border-gray-700">
                <nav class="flex flex-wrap sm:flex-nowrap gap-2" aria-label="Tabs">
                    <!-- Pestaña 1: Áreas de Trabajo -->
                    <button type="button"
                            wire:click="setActiveTab('areas')"
                            class="flex-1 inline-flex items-center justify-center py-3 px-4 rounded-xl text-sm font-bold transition-all gap-2.5 {{ $activeTab === 'areas' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/60' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <span>Áreas de Trabajo y Flujo</span>
                        <span class="py-0.5 px-2 rounded-full text-xs font-black {{ $activeTab === 'areas' ? 'bg-white/20 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                            {{ $areas->total() }}
                        </span>
                    </button>

                    <!-- Pestaña 2: Tipos de Vehículos -->
                    <button type="button"
                            wire:click="setActiveTab('vehicles')"
                            class="flex-1 inline-flex items-center justify-center py-3 px-4 rounded-xl text-sm font-bold transition-all gap-2.5 {{ $activeTab === 'vehicles' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/60' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        <span>Tipos de Vehículos</span>
                        <span class="py-0.5 px-2 rounded-full text-xs font-black {{ $activeTab === 'vehicles' ? 'bg-white/20 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                            {{ count($allowedVehicleTypes) }}
                        </span>
                    </button>

                    <!-- Pestaña 3: Datos del Taller -->
                    <button type="button"
                            wire:click="setActiveTab('general')"
                            class="flex-1 inline-flex items-center justify-center py-3 px-4 rounded-xl text-sm font-bold transition-all gap-2.5 {{ $activeTab === 'general' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/60' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span>Información y Datos del Taller</span>
                        <span class="py-0.5 px-2 rounded-full text-xs font-black {{ $activeTab === 'general' ? 'bg-white/20 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                            Perfil
                        </span>
                    </button>
                </nav>
            </div>

            <!-- CONTENIDO DE PESTAÑA 1: ÁREAS DE TRABAJO -->
            @if ($activeTab === 'areas')
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200/80 dark:border-gray-700 overflow-hidden">
                    
                    <!-- Barra de Acción de Áreas -->
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50/60 dark:bg-gray-800/80 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h3 class="text-base font-extrabold text-gray-900 dark:text-white flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
                                Flujo de Trabajo y Áreas Operativas
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                Define cómo avanzan las órdenes en el tablero Kanban, los estados automáticos y permisos de cobro/cotización.
                            </p>
                        </div>

                        <div class="flex items-center flex-wrap gap-2.5">
                            <button wire:click="loadRecommendedAreas"
                                    type="button"
                                    class="px-3.5 py-2 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/40 dark:hover:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300 text-xs font-bold rounded-xl border border-emerald-300 dark:border-emerald-700/60 transition flex items-center gap-1.5 shadow-xs">
                                <span>⚡</span>
                                <span>Cargar Áreas Recomendadas</span>
                            </button>

                            <button wire:click="openCreateAreaModal"
                                    type="button"
                                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold rounded-xl shadow-md shadow-indigo-600/20 transition flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                <span>Nueva Área</span>
                            </button>
                        </div>
                    </div>

                    <!-- Indicador de Área para Cotizaciones -->
                    @php
                        $quoteArea = $areas->firstWhere('can_generate_quotation', true);
                    @endphp
                    @if (!$quoteArea)
                        <div class="mx-6 mt-6 p-4 bg-amber-50 dark:bg-amber-950/30 border border-amber-300 dark:border-amber-700 rounded-xl text-xs text-amber-800 dark:text-amber-200 flex items-start gap-3">
                            <span class="text-xl">⚠️</span>
                            <div>
                                <p class="font-bold">Ninguna área tiene permisos para formular o editar cotizaciones</p>
                                <p class="mt-0.5 text-amber-700 dark:text-amber-300">
                                    Habilita el permiso <strong>"Cotiza"</strong> en el área correspondiente (ej. <em>Presupuesto / Diagnóstico</em>) para que el personal pueda fijar los montos de trabajo.
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="mx-6 mt-6 p-3 bg-indigo-50/70 dark:bg-indigo-950/20 border border-indigo-200 dark:border-indigo-800/50 rounded-xl text-xs text-indigo-800 dark:text-indigo-300 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span>💰</span>
                                <span>Área designada para formular cotizaciones:</span>
                                <strong class="font-bold text-indigo-900 dark:text-white underline">{{ $quoteArea->name }}</strong>
                            </div>
                            <span class="text-[11px] bg-indigo-100 dark:bg-indigo-900/60 text-indigo-800 dark:text-indigo-200 px-2.5 py-0.5 rounded-full font-bold">Activo</span>
                        </div>
                    @endif

                    <!-- TABLA MEJORADA DE ÁREAS -->
                    <div class="p-6 overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 border-b border-gray-200 dark:border-gray-700 pb-3">
                                    <th class="pb-3 px-4">Orden, Área & Permisos</th>
                                    <th class="pb-3 px-4">Estado Automático</th>
                                    <th class="pb-3 px-4 text-center">Órdenes Activas</th>
                                    <th class="pb-3 px-4">Usuarios Asignados</th>
                                    <th class="pb-3 px-4 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60 text-xs">
                                @forelse ($areas as $area)
                                    @php
                                        $statusConfig = [
                                            'recibido' => [
                                                'badge' => 'bg-slate-50 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border-slate-200 dark:border-slate-700',
                                                'dot' => 'bg-slate-400',
                                            ],
                                            'presupuesto' => [
                                                'badge' => 'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300 border-amber-200 dark:border-amber-800',
                                                'dot' => 'bg-amber-500',
                                            ],
                                            'en_espera' => [
                                                'badge' => 'bg-orange-50 text-orange-700 dark:bg-orange-950/40 dark:text-orange-300 border-orange-200 dark:border-orange-800',
                                                'dot' => 'bg-orange-500',
                                            ],
                                            'trabajando' => [
                                                'badge' => 'bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-300 border-blue-200 dark:border-blue-800',
                                                'dot' => 'bg-blue-500',
                                            ],
                                            'revision' => [
                                                'badge' => 'bg-purple-50 text-purple-700 dark:bg-purple-950/40 dark:text-purple-300 border-purple-200 dark:border-purple-800',
                                                'dot' => 'bg-purple-500',
                                            ],
                                            'terminado' => [
                                                'badge' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
                                                'dot' => 'bg-emerald-500',
                                            ],
                                        ];
                                        $cfg = $statusConfig[$area->default_status] ?? [
                                            'badge' => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 border-gray-200',
                                            'dot' => 'bg-gray-400',
                                        ];
                                    @endphp
                                    <tr class="hover:bg-gray-50/70 dark:hover:bg-gray-700/30 transition">
                                        <!-- ORDEN, ÁREA & PERMISOS EN SEGUNDA FILA -->
                                        <td class="py-4 px-4">
                                            <div class="flex items-start gap-3">
                                                <div class="w-8 h-8 rounded-xl bg-gray-100 dark:bg-gray-700/70 border border-gray-200 dark:border-gray-600 flex items-center justify-center font-mono text-xs font-bold text-gray-600 dark:text-gray-300 flex-shrink-0 mt-0.5">
                                                    #{{ $area->order ?? $loop->iteration }}
                                                </div>
                                                <div class="space-y-1">
                                                    <div class="font-extrabold text-sm text-gray-900 dark:text-white flex items-center gap-2">
                                                        <span>{{ $area->name }}</span>
                                                        @if ($area->is_delivery)
                                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                                                                🔑 Salida
                                                            </span>
                                                        @endif
                                                    </div>
                                                    @if ($area->description)
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 max-w-md">
                                                            {{ $area->description }}
                                                        </p>
                                                    @endif

                                                    <!-- SEGUNDA FILA: PERMISOS DEL ÁREA EN LÍNEA HORIZONTAL -->
                                                    <div class="pt-1.5 flex items-center flex-wrap gap-1.5">
                                                        <!-- Cotizar -->
                                                        <button wire:click="toggleAreaQuotation({{ $area->id }})"
                                                                type="button"
                                                                title="Alternar permiso para formular cotizaciones"
                                                                class="px-2.5 py-0.5 rounded-lg text-[10px] font-bold border transition-all flex items-center gap-1 {{ $area->can_generate_quotation ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/60 dark:text-amber-200 border-amber-300 dark:border-amber-700 shadow-xs' : 'bg-gray-100 text-gray-400 dark:bg-gray-800 dark:text-gray-500 border-gray-200 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                                                            <span>💰</span>
                                                            <span>{{ $area->can_generate_quotation ? 'Cotiza' : 'No cotiza' }}</span>
                                                        </button>

                                                        <!-- Ver Monto -->
                                                        <button wire:click="toggleAreaViewAmount({{ $area->id }})"
                                                                type="button"
                                                                title="Alternar visibilidad de montos a operarios"
                                                                class="px-2.5 py-0.5 rounded-lg text-[10px] font-bold border transition-all flex items-center gap-1 {{ $area->can_view_quotation_amount ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/60 dark:text-blue-200 border-blue-300 dark:border-blue-700 shadow-xs' : 'bg-gray-100 text-gray-400 dark:bg-gray-800 dark:text-gray-500 border-gray-200 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                                                            <span>👁️</span>
                                                            <span>{{ $area->can_view_quotation_amount ? 'Muestra Q' : 'Oculta Q' }}</span>
                                                        </button>

                                                        <!-- Cobrar -->
                                                        <button wire:click="toggleAreaCollectPayment({{ $area->id }})"
                                                                type="button"
                                                                title="Alternar cobro de pagos en esta área"
                                                                class="px-2.5 py-0.5 rounded-lg text-[10px] font-bold border transition-all flex items-center gap-1 {{ $area->can_collect_payment ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/60 dark:text-indigo-200 border-indigo-300 dark:border-indigo-700 shadow-xs' : 'bg-gray-100 text-gray-400 dark:bg-gray-800 dark:text-gray-500 border-gray-200 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                                                            <span>💳</span>
                                                            <span>{{ $area->can_collect_payment ? 'Cobra' : 'No cobra' }}</span>
                                                        </button>

                                                        <!-- Entrega -->
                                                        <button wire:click="toggleAreaDelivery({{ $area->id }})"
                                                                type="button"
                                                                title="Alternar si es área de entrega de vehículos"
                                                                class="px-2.5 py-0.5 rounded-lg text-[10px] font-bold border transition-all flex items-center gap-1 {{ $area->is_delivery ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/60 dark:text-emerald-200 border-emerald-300 dark:border-emerald-700 shadow-xs' : 'bg-gray-100 text-gray-400 dark:bg-gray-800 dark:text-gray-500 border-gray-200 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                                                            <span>🔑</span>
                                                            <span>{{ $area->is_delivery ? 'Entrega' : 'Operativa' }}</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- ESTADO AUTOMÁTICO EN KANBAN -->
                                        <td class="py-4 px-4 whitespace-nowrap">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border {{ $cfg['badge'] }}">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $cfg['dot'] }}"></span>
                                                <span>{{ $statusOptions[$area->default_status] ?? ucfirst($area->default_status) }}</span>
                                            </span>
                                        </td>

                                        <!-- ÓRDENES ACTIVAS -->
                                        <td class="py-4 px-4 whitespace-nowrap text-center">
                                            @if ($area->work_orders_count > 0)
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300 border border-amber-300 dark:border-amber-700/60"
                                                      title="No se puede borrar mientras contenga vehículos en proceso">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                    <span>{{ $area->work_orders_count }} {{ $area->work_orders_count === 1 ? 'caso' : 'casos' }}</span>
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                                    <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    <span>0 casos (Vacía)</span>
                                                </span>
                                            @endif
                                        </td>

                                        <!-- USUARIOS ASIGNADOS -->
                                        <td class="py-4 px-4">
                                            @if ($area->users->isNotEmpty())
                                                <div class="flex flex-wrap items-center gap-1 max-w-xs">
                                                    @foreach ($area->users as $u)
                                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 border border-gray-200 dark:border-gray-600">
                                                            <span>👤</span>
                                                            <span class="truncate max-w-[100px]">{{ $u->name }}</span>
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-xs text-gray-400 dark:text-gray-500 italic">
                                                    Acceso general (Todos)
                                                </span>
                                            @endif
                                        </td>

                                        <!-- ACCIONES -->
                                        <td class="py-4 px-4 whitespace-nowrap text-right">
                                            <div class="flex items-center justify-end gap-1.5">
                                                <!-- Editar -->
                                                <button wire:click="openEditAreaModal({{ $area->id }})"
                                                        type="button"
                                                        class="p-2 text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 rounded-xl transition"
                                                        title="Editar Área y Usuarios">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </button>

                                                <!-- Borrar (Condicionado a que esté vacía sin casos) -->
                                                @if ($area->work_orders_count > 0)
                                                    <button wire:click="confirmDeleteArea({{ $area->id }})"
                                                            type="button"
                                                            class="p-2 text-gray-400 hover:text-amber-600 dark:text-gray-500 hover:bg-amber-50 dark:hover:bg-amber-950/30 rounded-xl transition"
                                                            title="Área con {{ $area->work_orders_count }} casos activos. No puede borrarse hasta reubicar los vehículos.">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                        </svg>
                                                    </button>
                                                @else
                                                    <button wire:click="confirmDeleteArea({{ $area->id }})"
                                                            type="button"
                                                            class="p-2 text-rose-600 hover:text-rose-900 dark:text-rose-400 dark:hover:text-rose-200 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-xl transition"
                                                            title="Eliminar Área (Vacía)">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-12 text-center text-gray-400 dark:text-gray-500">
                                            <p class="font-bold text-sm">No hay áreas de trabajo configuradas.</p>
                                            <p class="text-xs mt-1">Haz clic en "Cargar Áreas Recomendadas" o "Nueva Área" para comenzar.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($areas->hasPages())
                        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700/60 bg-gray-50/50 dark:bg-gray-800/50">
                            {{ $areas->links() }}
                        </div>
                    @endif
                </div>
            @endif

            <!-- CONTENIDO DE PESTAÑA 2: TIPOS DE VEHÍCULOS -->
            @if ($activeTab === 'vehicles')
                @php
                    $vehicleIcons = [
                        'pasola' => '🛵',
                        'moto' => '🏍️',
                        'hashback' => '🚗',
                        'sedan' => '🚘',
                        'suv' => '🚙',
                        'pickup' => '🛻',
                        'pickup_double' => '🛻',
                        'van' => '🚐',
                        'camion' => '🚛',
                        'cabezal' => '🚚',
                        'bus_escolar' => '🚌',
                        'bus' => '🚍',
                    ];
                @endphp
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200/80 dark:border-gray-700 overflow-hidden">
                    <!-- Cabecera -->
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50/60 dark:bg-gray-800/80 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h3 class="text-base font-extrabold text-gray-900 dark:text-white flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
                                Tipos de Vehículos que Atiende la Empresa
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                Selecciona los tipos de vehículos que tu taller admite. En la pantalla de recepción solo se mostrarán las opciones activadas aquí.
                            </p>
                        </div>

                        <!-- Filtros Rápidos / Presets -->
                        <div class="flex flex-wrap items-center gap-1.5 bg-white dark:bg-gray-900/60 p-1.5 rounded-xl border border-gray-200 dark:border-gray-700 self-start md:self-auto">
                            <span class="text-[11px] font-bold text-gray-400 dark:text-gray-500 px-2 uppercase tracking-wider">Preajustes:</span>
                            <button type="button" wire:click="selectAllVehicleTypes" class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-gray-100 hover:bg-indigo-50 hover:text-indigo-600 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition">
                                Todos
                            </button>
                            <button type="button" wire:click="selectOnlyMotos" class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-gray-100 hover:bg-indigo-50 hover:text-indigo-600 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition">
                                Solo Motos
                            </button>
                            <button type="button" wire:click="selectOnlyLightVehicles" class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-gray-100 hover:bg-indigo-50 hover:text-indigo-600 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition">
                                Livianos
                            </button>
                            <button type="button" wire:click="selectOnlyHeavyVehicles" class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-gray-100 hover:bg-indigo-50 hover:text-indigo-600 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition">
                                Pesados
                            </button>
                            <button type="button" wire:click="clearVehicleTypes" class="px-2.5 py-1 text-xs font-semibold rounded-lg text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition">
                                Desmarcar
                            </button>
                        </div>
                    </div>

                    <!-- Grilla de Vehículos -->
                    <div class="p-6 sm:p-8">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                            @foreach ($allVehicleTypes as $key => $label)
                                @php
                                    $isSelected = in_array($key, $allowedVehicleTypes);
                                @endphp
                                <label class="relative flex items-center p-4 rounded-xl border-2 cursor-pointer transition-all duration-150 select-none {{ $isSelected ? 'border-indigo-500 bg-indigo-50/40 dark:bg-indigo-950/20 shadow-sm' : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 bg-white dark:bg-gray-900/40 opacity-75 hover:opacity-100' }}">
                                    <input type="checkbox"
                                           wire:model.live="allowedVehicleTypes"
                                           value="{{ $key }}"
                                           class="w-4 h-4 text-indigo-600 border-gray-300 dark:border-gray-600 rounded focus:ring-indigo-500 dark:focus:ring-indigo-400 bg-white dark:bg-gray-800 transition">
                                    <div class="ml-3 flex items-center gap-3">
                                        <span class="text-2xl filter drop-shadow-sm">{{ $vehicleIcons[$key] ?? '🚗' }}</span>
                                        <div>
                                            <p class="text-sm font-bold text-gray-800 dark:text-gray-200 leading-snug">
                                                {{ $label }}
                                            </p>
                                            <span class="text-[10px] font-mono uppercase tracking-wider {{ $isSelected ? 'text-indigo-600 dark:text-indigo-400 font-semibold' : 'text-gray-400 dark:text-gray-500' }}">
                                                {{ $key }}
                                            </span>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Barra Inferior de Guardado -->
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50/70 dark:bg-gray-800/80 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-2 text-xs">
                            @if (count($allowedVehicleTypes) === 0)
                                <span class="inline-flex items-center gap-1.5 text-amber-600 dark:text-amber-400 font-semibold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    Atención: Debe seleccionar al menos un tipo de vehículo.
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-gray-600 dark:text-gray-300">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    <strong>{{ count($allowedVehicleTypes) }}</strong> de {{ count($allVehicleTypes) }} tipos de vehículos seleccionados
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center gap-3 w-full sm:w-auto">
                            <button type="button"
                                    wire:click="saveVehicleTypes"
                                    wire:loading.attr="disabled"
                                    class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 active:scale-[0.98] transition shadow-md shadow-indigo-600/20 disabled:opacity-50 cursor-pointer">
                                <svg wire:loading.remove wire:target="saveVehicleTypes" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <svg wire:loading wire:target="saveVehicleTypes" class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                Guardar Tipos de Vehículo
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- CONTENIDO DE PESTAÑA 3: DATOS DEL TALLER -->
            @if ($activeTab === 'general')
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200/80 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50/60 dark:bg-gray-800/80">
                        <h3 class="text-base font-extrabold text-gray-900 dark:text-white flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
                            Identificación y Contacto Oficial del Taller
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                            Estos datos se imprimen en comprobantes de recepción, cotizaciones y encabezados para los clientes.
                        </p>
                    </div>

                    <form wire:submit.prevent="save" class="p-6 sm:p-8 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- Logo del Taller -->
                            <div class="md:col-span-2 p-4 bg-gray-50/70 dark:bg-gray-900/50 rounded-2xl border border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center gap-6">
                                <div class="flex-shrink-0">
                                    @if ($logo)
                                        <img class="h-20 w-20 object-contain rounded-2xl border-2 border-indigo-500 shadow-md bg-white dark:bg-gray-800 p-1" src="{{ $logo->temporaryUrl() }}" alt="Vista Previa">
                                    @elseif ($currentLogo)
                                        <img class="h-20 w-20 object-contain rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm bg-white dark:bg-gray-800 p-1" src="{{ Storage::url($currentLogo) }}" alt="Logo Actual">
                                    @else
                                        <div class="h-20 w-20 rounded-2xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 font-extrabold text-2xl border border-indigo-200 dark:border-indigo-800 flex items-center justify-center">
                                            🏢
                                        </div>
                                    @endif
                                </div>
                                <div class="space-y-1.5 flex-1">
                                    <label class="block text-xs font-bold text-gray-800 dark:text-gray-200">Logo del Taller</label>
                                    <p class="text-[11px] text-gray-500 dark:text-gray-400">Formatos recomendados: PNG o JPG con fondo transparente. Máximo 1MB.</p>
                                    <input type="file" wire:model="logo" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/60 dark:file:text-indigo-300 dark:hover:file:bg-indigo-800 transition cursor-pointer"/>
                                    @error('logo') <span class="text-rose-500 text-xs mt-1 block font-semibold">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Nombre Comercial -->
                            <div>
                                <x-label for="name" value="{{ __('Nombre Comercial del Taller *') }}" class="text-xs font-bold" />
                                <x-input id="name" type="text" class="mt-1 block w-full text-sm rounded-xl" wire:model="name" placeholder="Ej: Taller Mecánico Especializado" />
                                <x-input-error for="name" class="mt-1 text-xs" />
                            </div>

                            <!-- Enlace Público de Rastreo con Slug -->
                            <div>
                                <label class="block text-xs font-bold text-gray-800 dark:text-gray-200">Enlace Público de Rastreo (Sin Login)</label>
                                <div class="mt-1 flex rounded-xl shadow-xs">
                                    <span class="inline-flex items-center px-3 rounded-l-xl border border-r-0 border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-500 text-xs font-mono">
                                        /rastreo/
                                    </span>
                                    <input type="text" readonly value="{{ $slug }}" class="flex-1 block w-full min-w-0 rounded-none text-xs font-mono font-bold text-indigo-600 dark:text-indigo-400 bg-gray-50 dark:bg-gray-900 border-gray-300 dark:border-gray-700">
                                    <a href="{{ route('rastreo.tenant', $slug) }}" target="_blank" class="inline-flex items-center px-3 rounded-r-xl border border-l-0 border-indigo-600 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition" title="Abrir portal de rastreo público de este taller">
                                        <span>Probar ↗</span>
                                    </a>
                                </div>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">
                                    Comparte este enlace a tus clientes para que consulten el estatus de sus vehículos directamente en tu taller.
                                </p>
                            </div>

                            <!-- Administrador / Responsable -->
                            <div>
                                <x-label for="admin_name" value="{{ __('Nombre del Administrador / Responsable *') }}" class="text-xs font-bold" />
                                <x-input id="admin_name" type="text" class="mt-1 block w-full text-sm rounded-xl" wire:model="admin_name" placeholder="Ej: Juan Pérez" />
                                <x-input-error for="admin_name" class="mt-1 text-xs" />
                            </div>

                            <!-- Correo Oficial -->
                            <div>
                                <x-label for="email" value="{{ __('Correo Electrónico Oficial *') }}" class="text-xs font-bold" />
                                <x-input id="email" type="email" class="mt-1 block w-full text-sm rounded-xl" wire:model="email" placeholder="contacto@mitaller.com" />
                                <x-input-error for="email" class="mt-1 text-xs" />
                            </div>

                            <!-- Teléfono / WhatsApp -->
                            <div>
                                <x-label for="phone" value="{{ __('Teléfono / WhatsApp de Atención *') }}" class="text-xs font-bold" />
                                <x-input id="phone" type="text" class="mt-1 block w-full text-sm rounded-xl" wire:model="phone" placeholder="Ej: +502 5555 1234" />
                                <x-input-error for="phone" class="mt-1 text-xs" />
                            </div>

                            <!-- Configuración Multimoneda y Tasa de Cambio -->
                            <div class="md:col-span-2 p-5 bg-gradient-to-br from-indigo-50/70 via-white to-emerald-50/60 dark:from-gray-900/80 dark:via-gray-800 dark:to-emerald-950/20 rounded-2xl border border-indigo-100 dark:border-indigo-900/50 shadow-xs">
                                <div class="flex items-center justify-between mb-4 pb-3 border-b border-indigo-100/70 dark:border-gray-700">
                                    <div class="flex items-center gap-2.5">
                                        <div class="p-2 rounded-xl bg-indigo-600 text-white text-base shadow-sm">
                                            💱
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-gray-900 dark:text-white">Manejo Multimoneda & Tasa de Cambio</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Define la tasa oficial de conversión y la moneda base del taller.</p>
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-indigo-100 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">
                                        <span>Base: US$ 1.00 = Q{{ number_format((float) ($exchange_rate ?: 7.80), 2) }}</span>
                                    </span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <!-- Tasa de Cambio -->
                                    <div>
                                        <x-label for="exchange_rate" value="Tasa de Cambio (1 US$ a Quetzales) *" class="text-xs font-bold flex items-center justify-between">
                                            <span>Tasa de Cambio Oficial *</span>
                                            <span class="text-[11px] font-normal text-indigo-600 dark:text-indigo-400">1 US$ = X Quetzales</span>
                                        </x-label>
                                        <div class="relative mt-1">
                                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-xs font-bold text-gray-400">
                                                Q
                                            </span>
                                            <x-input id="exchange_rate" type="number" step="0.0001" min="0.01" max="999.99" class="block w-full pl-8 text-sm rounded-xl font-bold text-indigo-600 dark:text-indigo-400" wire:model.live.debounce.300ms="exchange_rate" placeholder="7.8000" />
                                        </div>
                                        <x-input-error for="exchange_rate" class="mt-1 text-xs" />
                                        <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">
                                            Por defecto <strong>7.80 Quetzales</strong> por cada dólar estadounidense (US$).
                                        </p>
                                    </div>

                                    <!-- Moneda Predeterminada del Taller -->
                                    <div>
                                        <x-label for="default_currency" value="Moneda Predeterminada de Facturación / Cotización *" class="text-xs font-bold" />
                                        <select id="default_currency" wire:model="default_currency" class="mt-1 block w-full text-sm rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 font-medium">
                                            <option value="GTQ">Quetzales de Guatemala (Q / GTQ)</option>
                                            <option value="USD">Dólares Estadounidenses (US$ / USD)</option>
                                        </select>
                                        <x-input-error for="default_currency" class="mt-1 text-xs" />
                                        <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">
                                            Los clientes y el personal podrán visualizar montos en ambas monedas simultáneamente.
                                        </p>
                                    </div>
                                </div>

                                <!-- Simulador en tiempo real de equivalencia -->
                                @php
                                    $simRate = (float) ($exchange_rate ?: 7.80);
                                @endphp
                                <div class="mt-4 p-3 bg-white/80 dark:bg-gray-800/90 rounded-xl border border-indigo-100/80 dark:border-gray-700 text-xs flex flex-wrap items-center justify-between gap-3">
                                    <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                        <span class="font-bold">Ejemplo de conversión con esta tasa:</span>
                                        <span class="px-2 py-0.5 rounded bg-gray-100 dark:bg-gray-700 font-mono font-bold text-indigo-600 dark:text-indigo-300">
                                            US$ 100.00 = Q{{ number_format(100 * $simRate, 2) }}
                                        </span>
                                        <span class="text-gray-400">|</span>
                                        <span class="px-2 py-0.5 rounded bg-gray-100 dark:bg-gray-700 font-mono font-bold text-emerald-600 dark:text-emerald-300">
                                            Q 1,000.00 = US$ {{ number_format($simRate > 0 ? 1000 / $simRate : 0, 2) }}
                                        </span>
                                    </div>
                                    <span class="text-[11px] text-indigo-600 dark:text-indigo-400 font-semibold">
                                        ⚡ Actualización en tiempo real
                                    </span>
                                </div>
                            </div>

                            <!-- Dirección Física -->
                            <div class="md:col-span-2">
                                <x-label for="address" value="{{ __('Dirección Física del Taller *') }}" class="text-xs font-bold" />
                                <x-input id="address" type="text" class="mt-1 block w-full text-sm rounded-xl" wire:model="address" placeholder="Ej: Km. 14.5 Carretera a El Salvador, Local 3" />
                                <x-input-error for="address" class="mt-1 text-xs" />
                            </div>

                        </div>

                        <div class="flex items-center justify-end pt-4 border-t border-gray-100 dark:border-gray-700">
                            <x-button class="bg-indigo-600 hover:bg-indigo-500 font-bold px-6 py-2.5 rounded-xl shadow-md shadow-indigo-600/20" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="save">Guardar Información</span>
                                <span wire:loading wire:target="save">Guardando...</span>
                            </x-button>
                        </div>
                    </form>
                </div>
            @endif

        </div>
    </div>

    <!-- MODAL: CREAR / EDITAR ÁREA DE TRABAJO -->
    <x-dialog-modal wire:model.live="managingAreaModal" maxWidth="3xl">
        <x-slot name="title">
            <div class="flex items-center gap-2">
                <div class="p-2 bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 rounded-xl">
                    🔧
                </div>
                <span class="font-extrabold text-gray-900 dark:text-white">
                    {{ $editingAreaId ? "Editar Área: {$areaName}" : 'Nueva Área de Taller' }}
                </span>
            </div>
        </x-slot>

        <x-slot name="content">
            <div class="space-y-5 max-h-[72vh] overflow-y-auto -mr-4 pr-4 custom-scrollbar">

                <!-- DATOS BÁSICOS -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-label for="areaName" value="Nombre del Área *" class="text-xs font-bold" />
                        <x-input id="areaName" type="text" class="mt-1 block w-full text-sm rounded-xl" wire:model.live.debounce.300ms="areaName" placeholder="Ej: Diagnóstico y Presupuestos" />
                        <x-input-error for="areaName" class="mt-1 text-xs" />
                    </div>

                    <div>
                        <x-label for="areaDefaultStatus" value="Estado Automático en Kanban *" class="text-xs font-bold" />
                        <select id="areaDefaultStatus" wire:model="areaDefaultStatus" class="mt-1 block w-full text-sm rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach ($statusOptions as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-input-error for="areaDefaultStatus" class="mt-1 text-xs" />
                    </div>

                    <div class="md:col-span-2">
                        <x-label for="areaDescription" value="Descripción / Responsabilidad (Opcional)" class="text-xs font-bold" />
                        <x-input id="areaDescription" type="text" class="mt-1 block w-full text-sm rounded-xl" wire:model="areaDescription" placeholder="Ej: Revisión visual, escaneo por computadora y presupuesto preliminar" />
                        <x-input-error for="areaDescription" class="mt-1 text-xs" />
                    </div>
                </div>

                <!-- CAPACIDADES Y PERMISOS DEL ÁREA -->
                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 block mb-3">
                        Capacidades y Permisos Operativos del Área
                    </span>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <!-- Cotizar -->
                        <div class="p-3.5 bg-amber-50/70 dark:bg-amber-950/20 rounded-xl border border-amber-200 dark:border-amber-800/60 flex items-start gap-2.5">
                            <input type="checkbox" id="modalCanGenerateQuotation" wire:model="areaCanGenerateQuotation" class="mt-0.5 rounded text-amber-600 focus:ring-amber-500 w-4 h-4">
                            <label for="modalCanGenerateQuotation" class="text-xs text-amber-900 dark:text-amber-200 cursor-pointer select-none">
                                <span class="font-bold block">💰 Generar / Editar Cotización</span>
                                <span class="text-[11px] text-amber-700 dark:text-amber-400">Permite establecer o modificar el monto presupuestado del trabajo en esta área.</span>
                            </label>
                        </div>

                        <!-- Ver Montos -->
                        <div class="p-3.5 bg-blue-50/70 dark:bg-blue-950/20 rounded-xl border border-blue-200 dark:border-blue-800/60 flex items-start gap-2.5">
                            <input type="checkbox" id="modalCanViewQuotationAmount" wire:model="areaCanViewQuotationAmount" class="mt-0.5 rounded text-blue-600 focus:ring-blue-500 w-4 h-4">
                            <label for="modalCanViewQuotationAmount" class="text-xs text-blue-900 dark:text-blue-200 cursor-pointer select-none">
                                <span class="font-bold block">👁️ Mostrar Montos al Personal</span>
                                <span class="text-[11px] text-blue-700 dark:text-blue-400">Si se desmarca, los operarios solo verán notas técnicas sin cifras económicas.</span>
                            </label>
                        </div>

                        <!-- Cobrar -->
                        <div class="p-3.5 bg-indigo-50/70 dark:bg-indigo-950/20 rounded-xl border border-indigo-200 dark:border-indigo-800/60 flex items-start gap-2.5">
                            <input type="checkbox" id="modalCanCollectPayment" wire:model="areaCanCollectPayment" class="mt-0.5 rounded text-indigo-600 focus:ring-indigo-500 w-4 h-4">
                            <label for="modalCanCollectPayment" class="text-xs text-indigo-900 dark:text-indigo-200 cursor-pointer select-none">
                                <span class="font-bold block">💳 Permitir Cobrar / Registrar Pagos</span>
                                <span class="text-[11px] text-indigo-700 dark:text-indigo-400">Habilita el botón de cobro y registro de abonos para los vehículos en esta área.</span>
                            </label>
                        </div>

                        <!-- Entrega -->
                        <div class="p-3.5 bg-emerald-50/70 dark:bg-emerald-950/30 rounded-xl border border-emerald-200 dark:border-emerald-800 flex items-start gap-2.5">
                            <input type="checkbox" id="modalIsDelivery" wire:model="areaIsDelivery" class="mt-0.5 rounded text-emerald-600 focus:ring-emerald-500 w-4 h-4">
                            <label for="modalIsDelivery" class="text-xs text-emerald-900 dark:text-emerald-200 cursor-pointer select-none">
                                <span class="font-bold block">🔑 Área de Entrega de Vehículo</span>
                                <span class="text-[11px] text-emerald-700 dark:text-emerald-400">Permite dar por entregado el vehículo al cliente y archivarlo del Kanban.</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- ASIGNACIÓN DE USUARIOS -->
                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 block">
                                Usuarios con Acceso a esta Área
                            </span>
                            <span class="text-[11px] text-gray-400">
                                Los usuarios seleccionados verán la columna en el Kanban. Si dejas a todos desmarcados, tendrá acceso general todo el personal.
                            </span>
                        </div>

                        <div class="flex items-center gap-2">
                            <button type="button" wire:click="selectAllUsersForArea" class="text-[11px] font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                                Marcar Todos
                            </button>
                            <span class="text-gray-300 dark:text-gray-600">|</span>
                            <button type="button" wire:click="clearUsersForArea" class="text-[11px] font-bold text-gray-500 dark:text-gray-400 hover:underline">
                                Desmarcar
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2.5 max-h-48 overflow-y-auto p-1 custom-scrollbar">
                        @forelse ($tenantUsers as $u)
                            <label class="p-2.5 rounded-xl border transition cursor-pointer select-none flex items-center justify-between {{ in_array($u->id, $areaSelectedUserIds) ? 'bg-indigo-50 dark:bg-indigo-950/50 border-indigo-300 dark:border-indigo-700 text-indigo-900 dark:text-indigo-200' : 'bg-gray-50 dark:bg-gray-800/60 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                <div class="flex items-center gap-2 truncate">
                                    <input type="checkbox" value="{{ $u->id }}" wire:model="areaSelectedUserIds" class="rounded text-indigo-600 focus:ring-indigo-500 w-4 h-4">
                                    <div class="truncate">
                                        <span class="font-bold text-xs block truncate">{{ $u->name }}</span>
                                        <span class="text-[10px] text-gray-400 block truncate">{{ ucfirst($u->role) }}</span>
                                    </div>
                                </div>
                            </label>
                        @empty
                            <div class="col-span-full py-4 text-center text-xs text-gray-400 italic">
                                No hay usuarios adicionales registrados en este taller.
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('managingAreaModal', false)">
                Cancelar
            </x-secondary-button>

            <x-button class="ms-3 bg-indigo-600 hover:bg-indigo-500" wire:click="saveArea">
                {{ $editingAreaId ? 'Actualizar Área' : 'Crear Área' }}
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <!-- MODAL: CONFIRMAR ELIMINACIÓN DE ÁREA (SOLO SI ESTÁ VACÍA) -->
    <x-confirmation-modal wire:model.live="confirmingAreaDeletion">
        <x-slot name="title">
            <div class="flex items-center gap-2 text-rose-600 dark:text-rose-400">
                <span>⚠️</span>
                <span>¿Eliminar Área de Trabajo?</span>
            </div>
        </x-slot>

        <x-slot name="content">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Estás a punto de eliminar el área <strong class="text-gray-900 dark:text-white">"{{ $areaToDeleteName }}"</strong> de forma permanente.
            </p>
            <div class="mt-3 p-3 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 rounded-xl text-xs text-emerald-800 dark:text-emerald-200">
                ✓ <strong>Verificación de seguridad superada:</strong> Esta área no tiene ninguna orden de trabajo asignada (0 casos activos).
            </div>
            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                Los usuarios asignados a esta área ya no verán esta columna en el tablero Kanban. Esta acción no se puede deshacer.
            </p>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmingAreaDeletion', false)">
                Cancelar
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="deleteArea">
                Sí, Eliminar Área
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>

</div>
