<div class="h-screen flex flex-col bg-gray-100 dark:bg-gray-900" x-data>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

    <!-- Top Bar -->
    <div class="px-6 py-4 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-center gap-3">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-black text-gray-900 dark:text-white tracking-tight flex items-center gap-2">
                    Tablero de Taller (Kanban)
                    @if (Auth::user()->accessibleAreaIds() !== null)
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300">
                            Mis Áreas Asignadas
                        </span>
                    @endif
                </h1>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Arrastra las órdenes entre áreas. El estado de la tarjeta cambiará automáticamente según el área.
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2.5">
            <!-- Botón Historial de Entregados -->
            <button wire:click="$set('showDeliveredModal', true)"
                    type="button"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-xs font-bold rounded-xl transition">
                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Historial de Entregados
            </button>

            <!-- Nueva Área (Admin) -->
            @if (Auth::user()->isAdmin())
                <button wire:click="$set('showCreateAreaModal', true)"
                        type="button"
                        class="inline-flex items-center gap-1 px-3 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-xs font-bold rounded-xl transition"
                        title="Agregar nueva área">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nueva Área
                </button>
            @endif

            <!-- Nueva Recepción -->
            <a href="{{ route('reception.create') }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-md shadow-indigo-600/20 hover:shadow-indigo-600/30 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nueva Recepción
            </a>
        </div>
    </div>

    <!-- Kanban Board Container -->
    <div class="flex-1 overflow-x-auto overflow-y-hidden bg-gray-100 dark:bg-gray-900 p-6"
         x-data="{
             initBoard() {
                 new Sortable(this.$refs.board, {
                     animation: 150,
                     handle: '.column-header',
                     ghostClass: 'opacity-50',
                     onEnd: (evt) => {
                         let orderedIds = Array.from(evt.to.children).map(el => el.dataset.areaId).filter(Boolean);
                         if (orderedIds.length > 0) {
                             $wire.updateAreaOrders(orderedIds);
                         }
                     }
                 });
             },
             initColumn(el, areaId) {
                 new Sortable(el, {
                     group: 'shared',
                     animation: 150,
                     ghostClass: 'bg-indigo-50/50',
                     onEnd: (evt) => {
                         let itemEl = evt.item;
                         let newAreaId = evt.to.dataset.areaId;
                         let oldAreaId = evt.from.dataset.areaId;
                         let orderId = itemEl.dataset.orderId;

                         if (newAreaId && oldAreaId && newAreaId !== oldAreaId) {
                             $wire.updateOrderArea(orderId, newAreaId);
                         }
                     }
                 });
             }
         }"
         x-init="initBoard()">
        
        <div class="flex h-full space-x-4 items-start pb-4" x-ref="board">
            @forelse ($areas as $area)
                @php
                    $isDeliveryArea = $area->is_delivery || str_contains(mb_strtolower($area->name), 'entreg');
                @endphp
                <div class="flex-shrink-0 w-84 bg-gray-200/80 dark:bg-gray-800/90 rounded-2xl flex flex-col max-h-full border {{ $isDeliveryArea ? 'border-emerald-400 dark:border-emerald-600' : 'border-gray-200 dark:border-gray-700' }} shadow-sm transition"
                     data-area-id="{{ $area->id }}">

                    <!-- Column Header -->
                    <div class="column-header p-3.5 {{ $isDeliveryArea ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-900 dark:text-emerald-200' : 'bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-100' }} rounded-t-2xl font-bold text-sm flex justify-between items-center cursor-grab active:cursor-grabbing border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-2 truncate">
                            @if ($isDeliveryArea)
                                <span class="p-1 bg-emerald-500 text-white rounded-lg text-xs" title="Área de Entrega">
                                    🔑
                                </span>
                            @endif
                            <span class="truncate font-black">{{ $area->name }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-extrabold px-2 py-0.5 rounded-full {{ $isDeliveryArea ? 'bg-emerald-200 text-emerald-900 dark:bg-emerald-800 dark:text-emerald-100' : 'bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                                {{ $area->workOrders->count() }}
                            </span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                            </svg>
                        </div>
                    </div>

                    <!-- Cards Container -->
                    <div class="flex-1 overflow-y-auto p-3 space-y-3 min-h-[140px]"
                         data-area-id="{{ $area->id }}"
                         x-init="initColumn($el, {{ $area->id }})">

                        @forelse ($area->workOrders as $order)
                            @php
                                $statusKey = $order->status;
                                $statusStyles = [
                                    'recibido' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/60 dark:text-blue-300 border-blue-200 dark:border-blue-800',
                                    'presupuesto' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/60 dark:text-amber-300 border-amber-200 dark:border-amber-800',
                                    'en_espera' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/60 dark:text-orange-300 border-orange-200 dark:border-orange-800',
                                    'trabajando' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/60 dark:text-indigo-300 border-indigo-200 dark:border-indigo-800',
                                    'revision' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/60 dark:text-purple-300 border-purple-200 dark:border-purple-800',
                                    'terminado' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/60 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
                                ];
                                $statusClass = $statusStyles[$statusKey] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 border-gray-300';
                            @endphp

                            <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm hover:shadow-md border border-gray-200 dark:border-gray-700 cursor-grab active:cursor-grabbing transition-all select-none group relative"
                                 data-order-id="{{ $order->id }}">

                                <!-- Top Row: Placa y Tiempo -->
                                <div class="flex justify-between items-start mb-1.5">
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-sm font-black text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded font-mono">
                                            {{ $order->vehicle->plate ?? 'S/P' }}
                                        </span>
                                        <span class="text-[11px] text-gray-400 font-mono">#{{ $order->id }}</span>
                                    </div>
                                    <span class="text-[11px] text-gray-400 dark:text-gray-500">
                                        {{ $order->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                <!-- Datos del Vehículo -->
                                <div class="text-sm font-bold text-gray-800 dark:text-gray-100 line-clamp-1 cursor-pointer"
                                     wire:click="selectOrder({{ $order->id }})">
                                    {{ $order->vehicle->brand ?? '' }} {{ $order->vehicle->model ?? '' }}
                                </div>

                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 flex items-center justify-between">
                                    <span class="truncate">👤 {{ $order->vehicle->owner_name ?? 'Particular' }}</span>
                                    @if ($order->vehicle->color)
                                        <span class="text-[11px] text-gray-400">{{ $order->vehicle->color }}</span>
                                    @endif
                                </div>

                                <!-- Indicadores: Estado Actual & Estado de Pago -->
                                <div class="mt-3 pt-2.5 border-t border-gray-100 dark:border-gray-700 flex flex-wrap items-center justify-between gap-1.5">
                                    <!-- Badge de Estado Cambiante según Área -->
                                    <span class="text-[11px] font-bold px-2 py-0.5 rounded-full border {{ $statusClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $statusKey)) }}
                                    </span>

                                    <!-- Badge de Pago -->
                                    @if ($order->payment_status === 'pagado')
                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 px-2 py-0.5 rounded-full border border-emerald-300 dark:border-emerald-800"
                                              title="Pago Completo Registrado">
                                            ✓ Pagado
                                        </span>
                                    @elseif ($order->payment_status === 'parcial')
                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/40 px-2 py-0.5 rounded-full border border-amber-300 dark:border-amber-800"
                                              title="Pago Parcial (@currency($order->paid_amount) de @currency($order->total_amount))">
                                            🟡 Parcial
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-[11px] font-medium text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/50 px-2 py-0.5 rounded-full border border-gray-200 dark:border-gray-600"
                                              title="Pago Pendiente">
                                            💳 Pendiente
                                        </span>
                                    @endif
                                </div>

                                <!-- Botones de Acción Rápida -->
                                <div class="mt-2.5 flex items-center justify-between gap-1.5 pt-2 border-t border-gray-100 dark:border-gray-700/60">
                                    <!-- Registrar Pago Rápido (Condicionado a permisos de cobrar en el área) -->
                                    @if ($this->canCollectPaymentForOrder($order))
                                        <button wire:click.stop="openPaymentModal({{ $order->id }})"
                                                type="button"
                                                class="inline-flex items-center gap-1 text-[11px] font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 py-1 px-2 rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-950/40 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            @if ($this->canViewOrderQuotationAmount($order) && $order->total_amount)
                                                @currency($order->total_amount)
                                            @else
                                                Cobrar
                                            @endif
                                        </button>
                                    @elseif ($this->canViewOrderQuotationAmount($order) && $order->total_amount)
                                        <span class="text-[11px] font-bold text-gray-700 dark:text-gray-300 px-2 py-1 bg-gray-100 dark:bg-gray-700/50 rounded-lg">
                                            @currency($order->total_amount)
                                        </span>
                                    @else
                                        <span></span>
                                    @endif

                                    <!-- Botón Entregar Vehículo (Destacado en área de entrega o si está listo) -->
                                    @if ($isDeliveryArea || in_array($order->status, ['terminado', 'revision']))
                                        <button wire:click.stop="openDeliveryModal({{ $order->id }})"
                                                type="button"
                                                class="inline-flex items-center gap-1 text-[11px] font-black text-white bg-emerald-600 hover:bg-emerald-700 px-2.5 py-1 rounded-lg shadow-sm transition">
                                            🔑 Entregar
                                        </button>
                                    @else
                                        <button wire:click.stop="selectOrder({{ $order->id }})"
                                                type="button"
                                                class="text-[11px] font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                            Ver detalle →
                                        </button>
                                    @endif
                                </div>

                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-400 dark:text-gray-500 text-xs italic">
                                Sin órdenes en esta área
                            </div>
                        @endforelse

                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center text-gray-500 dark:text-gray-400">
                    <p class="font-bold">No hay áreas configuradas para este taller o asignadas a tu usuario.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Modal Detalle Ampliado de la Orden -->
    <x-dialog-modal wire:model.live="showOrderModal" maxWidth="5xl">
        <x-slot name="title">
            @if ($selectedOrder)
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="text-xl font-black text-gray-900 dark:text-white">Orden #{{ $selectedOrder->id }}</span>
                        <!-- Placa destacada con estilo de matrícula -->
                        <span class="text-base font-mono font-black tracking-wider bg-amber-300 text-gray-900 px-3 py-1 rounded-md border-2 border-gray-900 shadow-sm">
                            {{ $selectedOrder->vehicle->plate ?? 'S/P' }}
                        </span>
                        <span class="text-sm font-bold text-gray-600 dark:text-gray-300">
                            {{ $selectedOrder->vehicle->brand ?? '' }} {{ $selectedOrder->vehicle->model ?? '' }}
                        </span>
                    </div>

                    <div class="flex items-center gap-2">
                        @if ($selectedOrder->area)
                            <span class="text-xs font-bold px-3 py-1 rounded-full bg-purple-100 text-purple-800 dark:bg-purple-950 dark:text-purple-300 border border-purple-200 dark:border-purple-800 flex items-center gap-1">
                                📍 Área: {{ $selectedOrder->area->name }}
                            </span>
                        @endif
                        <span class="text-xs font-bold px-3 py-1 rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900/60 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">
                            {{ ucfirst(str_replace('_', ' ', $selectedOrder->status)) }}
                        </span>
                    </div>
                </div>
            @endif
        </x-slot>

        <x-slot name="content">
            @if ($selectedOrder)
                <div class="space-y-6 max-h-[76vh] overflow-y-auto -mr-6 pr-6 pl-0.5 custom-scrollbar">

                    <!-- SECCIÓN: TRABAJO A REALIZAR / REQUERIMIENTO DEL CLIENTE -->
                    <div class="p-4 bg-gradient-to-r from-indigo-50/80 to-blue-50/80 dark:from-indigo-950/40 dark:to-blue-950/40 rounded-2xl border border-indigo-200 dark:border-indigo-800/60">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-xs font-black uppercase tracking-wider text-indigo-700 dark:text-indigo-300 flex items-center gap-2">
                                <span>🛠️</span>
                                <span>Trabajo Solicitado / Requerimiento del Cliente</span>
                            </h3>
                            @if ($selectedOrder->payment_type)
                                <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700">
                                    Tipo: {{ ucfirst($selectedOrder->payment_type) }}
                                    @if ($selectedOrder->insurance_company)
                                        ({{ $selectedOrder->insurance_company }})
                                    @endif
                                </span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-800 dark:text-gray-200 whitespace-pre-line bg-white/70 dark:bg-gray-900/60 p-3 rounded-xl border border-indigo-100 dark:border-indigo-900/50">
                            {{ $selectedOrder->notes ?: 'Sin descripción detallada de requerimientos anotada en la recepción.' }}
                        </p>
                    </div>

                    <!-- GRID PRINCIPAL DE 3 COLUMNAS -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                        <!-- COLUMNA 1: FICHA TÉCNICA Y CLIENTE -->
                        <div class="p-4 bg-gray-50 dark:bg-gray-800/70 rounded-2xl border border-gray-200 dark:border-gray-700 flex flex-col justify-between">
                            <div>
                                <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-3 flex items-center gap-1.5">
                                    <span>🚗</span>
                                    <span>Vehículo y Cliente</span>
                                </h4>

                                <div class="space-y-2.5 text-xs">
                                    <div>
                                        <span class="text-gray-400 text-[10px] uppercase font-bold block">Vehículo</span>
                                        <p class="font-bold text-gray-900 dark:text-white text-sm">
                                            {{ $selectedOrder->vehicle->brand }} {{ $selectedOrder->vehicle->model }}
                                            <span class="text-gray-500 font-normal">({{ $selectedOrder->vehicle->year ?? 'N/D' }})</span>
                                        </p>
                                        <p class="text-gray-500 dark:text-gray-400 mt-0.5">
                                            Color: {{ $selectedOrder->vehicle->color ?? 'N/D' }} • {{ ucfirst(str_replace('_', ' ', $selectedOrder->vehicle->vehicle_type ?? 'sedan')) }}
                                        </p>
                                    </div>

                                    <div class="pt-2 border-t border-gray-200 dark:border-gray-700/60 grid grid-cols-2 gap-2">
                                        <div>
                                            <span class="text-gray-400 text-[10px] uppercase font-bold block">Energía / Motor</span>
                                            <span class="font-bold text-gray-800 dark:text-gray-200">
                                                @if (($selectedOrder->vehicle->fuel_type ?? '') === 'electrico')
                                                    ⚡ Eléctrico
                                                @elseif (($selectedOrder->vehicle->fuel_type ?? '') === 'diesel')
                                                    🛢️ Diésel
                                                @else
                                                    ⛽ Gasolina
                                                @endif
                                            </span>
                                        </div>
                                        <div>
                                            <span class="text-gray-400 text-[10px] uppercase font-bold block">Kilometraje</span>
                                            <span class="font-bold text-gray-800 dark:text-gray-200">
                                                {{ $selectedOrder->mileage ? number_format($selectedOrder->mileage) . ' km' : 'N/D' }}
                                            </span>
                                        </div>
                                    </div>

                                    @if ($selectedOrder->fuel_level)
                                        <div>
                                            <span class="text-gray-400 text-[10px] uppercase font-bold block">Nivel de Combustible / Carga</span>
                                            <span class="font-mono font-bold px-2 py-0.5 rounded bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 inline-block mt-0.5">
                                                {{ $selectedOrder->fuel_level }}
                                            </span>
                                        </div>
                                    @endif

                                    <div class="pt-2 border-t border-gray-200 dark:border-gray-700/60">
                                        <span class="text-gray-400 text-[10px] uppercase font-bold block">Propietario / Contacto</span>
                                        <p class="font-bold text-gray-900 dark:text-white">
                                            {{ $selectedOrder->vehicle->owner_name ?? 'Particular' }}
                                        </p>
                                        @if (Auth::user()->can_view_contact_info)
                                            @if ($selectedOrder->vehicle->owner_phone)
                                                <p class="text-gray-600 dark:text-gray-300 mt-0.5 flex items-center gap-1">
                                                    📞 {{ $selectedOrder->vehicle->owner_phone }}
                                                </p>
                                            @endif
                                            @if ($selectedOrder->vehicle->owner_email)
                                                <p class="text-gray-600 dark:text-gray-300 mt-0.5">
                                                    ✉️ {{ $selectedOrder->vehicle->owner_email }}
                                                </p>
                                            @endif
                                        @else
                                            <span class="text-[10px] text-gray-400 italic block mt-0.5">
                                                🔒 Contacto reservado a recepción
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- COLUMNA 2: ÁREA DE TRABAJO & GENERAR COTIZACIÓN -->
                        <div class="p-4 bg-gray-50 dark:bg-gray-800/70 rounded-2xl border border-gray-200 dark:border-gray-700 flex flex-col justify-between">
                            <div>
                                <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-3 flex items-center justify-between">
                                    <span class="flex items-center gap-1.5">
                                        <span>📍</span>
                                        <span>Área y Cotización</span>
                                    </span>
                                    @if ($selectedOrder->area)
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-purple-50 text-purple-700 dark:bg-purple-950/60 dark:text-purple-300 border border-purple-200 dark:border-purple-800">
                                            {{ $selectedOrder->area->name }}
                                        </span>
                                    @endif
                                </h4>

                                <!-- Permiso para Generar Cotización en esta Área -->
                                @if ($this->canGenerateQuotationForOrder($selectedOrder))
                                    <div class="p-3 bg-indigo-50/80 dark:bg-indigo-950/40 rounded-xl border border-indigo-200 dark:border-indigo-800/60 text-xs mb-3">
                                        <div class="flex items-center gap-1.5 text-indigo-700 dark:text-indigo-300 font-bold mb-1">
                                            <span>📝</span>
                                            <span>Área Autorizada para Cotizar</span>
                                        </div>
                                        <p class="text-[11px] text-gray-600 dark:text-gray-300">
                                            Esta área tiene permisos para formular o actualizar el presupuesto de reparación.
                                        </p>
                                    </div>

                                    @if ($editingQuotation)
                                        <div class="space-y-3 bg-white dark:bg-gray-900 p-3 rounded-xl border border-indigo-300 dark:border-indigo-700">
                                            <div>
                                                <x-label value="Monto Total Cotizado (Q) *" class="text-xs font-bold" />
                                                <x-input type="number" step="0.01" wire:model="quotationTotalAmount" class="mt-1 block w-full text-sm font-bold" placeholder="0.00" />
                                                <x-input-error for="quotationTotalAmount" class="text-xs mt-1" />
                                            </div>

                                            <div>
                                                <x-label value="Detalle de Trabajos y Repuestos" class="text-xs font-bold" />
                                                <textarea wire:model="quotationNotes" rows="3" class="mt-1 block w-full text-xs rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-gray-800 dark:text-gray-200" placeholder="Mano de obra, repuestos necesarios, etc."></textarea>
                                                <x-input-error for="quotationNotes" class="text-xs mt-1" />
                                            </div>

                                            <div class="flex items-center justify-end gap-2 pt-1">
                                                <button type="button" wire:click="cancelEditQuotation" class="px-2.5 py-1 text-xs text-gray-600 dark:text-gray-400 hover:bg-gray-100 rounded-lg">
                                                    Cancelar
                                                </button>
                                                <button type="button" wire:click="saveQuotation" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm">
                                                    💾 Guardar
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <div class="space-y-2">
                                            <div class="p-3 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700">
                                                <span class="text-gray-400 text-[10px] uppercase font-bold block">Cotización Asignada</span>
                                                <span class="text-lg font-black text-gray-900 dark:text-white">
                                                    {{ $selectedOrder->total_amount ? 'Q' . number_format($selectedOrder->total_amount, 2) : 'Pendiente de cotizar' }}
                                                </span>
                                            </div>

                                            <button type="button" wire:click="editQuotation" class="w-full py-2 px-3 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900/40 dark:hover:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 font-bold text-xs rounded-xl border border-indigo-200 dark:border-indigo-800 transition flex items-center justify-center gap-1.5">
                                                <span>✏️</span>
                                                <span>Modificar / Ingresar Cotización</span>
                                            </button>
                                        </div>
                                    @endif

                                @else
                                    <!-- Área no autorizada para cotizar -->
                                    <div class="p-3.5 bg-gray-100/80 dark:bg-gray-900/60 rounded-xl border border-gray-200 dark:border-gray-700 text-xs">
                                        <div class="flex items-center gap-1.5 text-gray-700 dark:text-gray-300 font-bold mb-1.5">
                                            <span>🔒</span>
                                            <span>Área de Ejecución Técnica</span>
                                        </div>
                                        <p class="text-gray-500 dark:text-gray-400 text-[11px] leading-relaxed">
                                            Esta área no tiene permiso para generar cotizaciones.
                                            @php
                                                $quoteArea = $this->getQuotationArea();
                                            @endphp
                                            @if ($quoteArea)
                                                La cotización se formula en el área: <strong class="text-indigo-600 dark:text-indigo-400">{{ $quoteArea->name }}</strong>.
                                            @else
                                                <span class="text-amber-600 dark:text-amber-400 font-semibold">(Ninguna área del taller tiene permiso de cotizar actualmente).</span>
                                            @endif
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- COLUMNA 3: INFORMACIÓN FINANCIERA Y COBROS -->
                        <div class="p-4 bg-gray-50 dark:bg-gray-800/70 rounded-2xl border border-gray-200 dark:border-gray-700 flex flex-col justify-between">
                            <div>
                                <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-3 flex items-center justify-between">
                                    <span class="flex items-center gap-1.5">
                                        <span>💰</span>
                                        <span>Finanzas y Pagos</span>
                                    </span>
                                </h4>

                                @if ($this->canViewOrderQuotationAmount($selectedOrder))
                                    <!-- Montos Visibles -->
                                    <div class="space-y-3">
                                        <div class="p-3 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700">
                                            <div class="flex items-center justify-between text-xs">
                                                <span class="text-gray-400 font-bold">Total Cotización:</span>
                                                <span class="font-black text-gray-900 dark:text-white">
                                                    {{ $selectedOrder->total_amount ? \App\Helpers\CurrencyHelper::formatDual($selectedOrder->total_amount) : 'Q 0.00' }}
                                                </span>
                                            </div>
                                            <div class="flex items-center justify-between text-xs mt-1.5">
                                                <span class="text-gray-400 font-bold">Total Abonado:</span>
                                                <span class="font-black text-emerald-600 dark:text-emerald-400">
                                                    @currencyDual($selectedOrder->paid_amount ?? 0)
                                                </span>
                                            </div>
                                            <div class="flex items-center justify-between text-xs mt-1.5 pt-1.5 border-t border-gray-100 dark:border-gray-800">
                                                <span class="text-gray-500 dark:text-gray-400 font-bold">Saldo Pendiente:</span>
                                                <span class="font-black text-rose-600 dark:text-rose-400">
                                                    @if($selectedOrder->pendingPaymentAmount() > 0)
                                                        @currencyDual($selectedOrder->pendingPaymentAmount())
                                                    @else
                                                        ✓ Solvente
                                                    @endif
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Badge de Estado de Pago -->
                                        <div class="flex items-center justify-between p-2.5 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 text-xs">
                                            <span class="text-gray-400 font-bold">Estado de Pago:</span>
                                            @if ($selectedOrder->payment_status === 'pagado')
                                                <span class="font-black text-emerald-600 bg-emerald-50 dark:bg-emerald-950/50 px-2 py-0.5 rounded-full border border-emerald-300">
                                                    ✓ Pagado
                                                </span>
                                            @elseif ($selectedOrder->payment_status === 'parcial')
                                                <span class="font-black text-amber-600 bg-amber-50 dark:bg-amber-950/50 px-2 py-0.5 rounded-full border border-amber-300">
                                                    🟡 Pago Parcial
                                                </span>
                                            @else
                                                <span class="font-bold text-gray-500 bg-gray-100 dark:bg-gray-800 px-2 py-0.5 rounded-full">
                                                    💳 Pendiente
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Botón de Cobro según permiso can_collect_payment -->
                                        @if ($this->canCollectPaymentForOrder($selectedOrder))
                                            <button wire:click="openPaymentModal({{ $selectedOrder->id }})"
                                                    type="button"
                                                    class="w-full py-2.5 px-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow transition flex items-center justify-center gap-1.5">
                                                <span>💳</span>
                                                <span>Registrar / Cobrar Pago</span>
                                            </button>
                                        @else
                                            <div class="p-2.5 bg-gray-100 dark:bg-gray-900 rounded-xl text-[11px] text-gray-500 dark:text-gray-400 text-center italic">
                                                El cobro está habilitado en áreas autorizadas (Recepción / Entrega).
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <!-- Monto Oculto para Operarios de esta Área -->
                                    <div class="p-4 bg-gray-100/70 dark:bg-gray-900/60 rounded-xl border border-gray-200 dark:border-gray-700 text-center space-y-2">
                                        <span class="text-2xl block">🔒</span>
                                        <p class="text-xs font-bold text-gray-800 dark:text-gray-200">
                                            Montos Económicos Ocultos
                                        </p>
                                        <p class="text-[11px] text-gray-500 dark:text-gray-400 leading-relaxed">
                                            Los valores de cotización y pagos están reservados para áreas autorizadas (Administración, Recepción o Presupuestos).
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>

                    <!-- SECCIÓN: CHECKLIST DE NOVEDADES Y DAÑOS -->
                    @if ($selectedOrder->checklists && $selectedOrder->checklists->isNotEmpty())
                        <div class="p-4 bg-gray-50 dark:bg-gray-800/70 rounded-2xl border border-gray-200 dark:border-gray-700">
                            <h4 class="font-bold text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2 flex items-center gap-2">
                                <span>📋</span>
                                <span>Checklist de Recepción y Anomalías Reportadas</span>
                            </h4>
                            <div class="max-h-44 overflow-y-auto space-y-1.5 text-xs custom-scrollbar">
                                @php
                                    $hasIssues = false;
                                @endphp
                                @foreach ($selectedOrder->checklists as $check)
                                    @if ($check->status !== 'correct')
                                        @php $hasIssues = true; @endphp
                                        <div class="flex items-center justify-between p-2 bg-rose-50/70 dark:bg-rose-950/30 rounded-lg border border-rose-200 dark:border-rose-900/50">
                                            <div class="flex items-center gap-2">
                                                <span class="text-rose-500 font-bold">⚠️</span>
                                                <span class="font-bold text-gray-900 dark:text-white">{{ $check->group_name }}:</span>
                                                <span class="text-gray-700 dark:text-gray-300">{{ $check->item_name }}</span>
                                            </div>
                                            <span class="font-black uppercase text-[10px] text-rose-700 dark:text-rose-300 px-2 py-0.5 rounded bg-rose-100 dark:bg-rose-900/60">
                                                {{ $check->status }}
                                            </span>
                                        </div>
                                    @endif
                                @endforeach
                                @if (!$hasIssues)
                                    <p class="text-xs text-gray-500 dark:text-gray-400 italic">
                                        Todos los elementos de la inspección inicial fueron aprobados en estado correcto.
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endif

                </div>
            @endif
        </x-slot>

        <x-slot name="footer">
            <div class="w-full flex items-center justify-between">
                @if ($selectedOrder && ($selectedOrder->area?->is_delivery || in_array($selectedOrder->status, ['terminado', 'revision'])))
                    <button wire:click="openDeliveryModal({{ $selectedOrder->id }})"
                            type="button"
                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black rounded-xl shadow transition flex items-center gap-1.5">
                        <span>🔑</span>
                        <span>Entregar Vehículo al Cliente</span>
                    </button>
                @else
                    <div></div>
                @endif

                <x-secondary-button wire:click="$set('showOrderModal', false)">
                    Cerrar
                </x-secondary-button>
            </div>
        </x-slot>
    </x-dialog-modal>

    <!-- Modal de Registro de Pago -->
    <x-dialog-modal wire:model.live="showPaymentModal" maxWidth="md">
        <x-slot name="title">
            <div class="flex items-center gap-2">
                <div class="p-2 bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 rounded-lg">
                    💳
                </div>
                <span class="font-bold text-gray-900 dark:text-white">Registrar Pago de Reparación</span>
            </div>
        </x-slot>

        <x-slot name="content">
            @if ($paymentOrder)
                <div class="space-y-4">
                    <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-xl text-xs flex justify-between items-center">
                        <div>
                            <span class="font-bold text-gray-900 dark:text-white">Orden #{{ $paymentOrder->id }}</span>
                            <span class="text-gray-500 dark:text-gray-400 block">{{ $paymentOrder->vehicle?->plate }} - {{ $paymentOrder->vehicle?->brand }} {{ $paymentOrder->vehicle?->model }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-gray-400 block">Propietario</span>
                            <span class="font-bold text-gray-800 dark:text-gray-200">{{ $paymentOrder->vehicle?->owner_name ?? 'Particular' }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <x-label for="paymentTotalAmount" value="Monto Total Cotizado *" class="text-xs font-semibold" />
                            <x-input id="paymentTotalAmount" type="number" step="0.01" min="0" class="mt-1 block w-full text-sm font-mono" wire:model="paymentTotalAmount" placeholder="0.00" />
                            <x-input-error for="paymentTotalAmount" class="mt-1 text-xs" />
                        </div>
                        <div>
                            <x-label for="paymentPaidAmount" value="Monto Pagado / Abonado *" class="text-xs font-semibold" />
                            <x-input id="paymentPaidAmount" type="number" step="0.01" min="0" class="mt-1 block w-full text-sm font-mono" wire:model="paymentPaidAmount" placeholder="0.00" />
                            <x-input-error for="paymentPaidAmount" class="mt-1 text-xs" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <x-label for="paymentMethod" value="Método de Pago *" class="text-xs font-semibold" />
                            <select id="paymentMethod" wire:model="paymentMethod" class="mt-1 block w-full text-sm rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                <option value="efectivo">Efectivo</option>
                                <option value="tarjeta">Tarjeta de Crédito / Débito</option>
                                <option value="transferencia">Transferencia Bancaria</option>
                                <option value="cheque">Cheque</option>
                                <option value="aseguradora">Aseguradora</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>
                        <div>
                            <x-label for="paymentStatus" value="Estado del Pago *" class="text-xs font-semibold" />
                            <select id="paymentStatus" wire:model="paymentStatus" class="mt-1 block w-full text-sm rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                <option value="pendiente">Pendiente</option>
                                <option value="parcial">Pago Parcial</option>
                                <option value="pagado">Pagado Total</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <x-label for="paymentReference" value="No. de Factura / Referencia / Comprobante" class="text-xs font-semibold" />
                        <x-input id="paymentReference" type="text" class="mt-1 block w-full text-sm" wire:model="paymentReference" placeholder="Ej: Factura 001-982 o Transf. #45678" />
                        <x-input-error for="paymentReference" class="mt-1 text-xs" />
                    </div>
                </div>
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('showPaymentModal', false)">
                Cancelar
            </x-secondary-button>

            <x-button class="ms-3 bg-emerald-600 hover:bg-emerald-700" wire:click="savePayment">
                Guardar Pago
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Modal de Confirmación de Entrega de Vehículo -->
    <x-dialog-modal wire:model.live="showDeliveryModal" maxWidth="lg">
        <x-slot name="title">
            <div class="flex items-center gap-2.5">
                <div class="p-2 bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 rounded-xl text-lg flex items-center justify-center">
                    🔑
                </div>
                <div>
                    <h3 class="font-black text-gray-900 dark:text-white text-base leading-snug">Confirmar Entrega de Vehículo</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-normal">Entrega de la unidad terminada al cliente</p>
                </div>
            </div>
        </x-slot>

        <x-slot name="content">
            @if ($deliveryOrder)
                <div class="space-y-4">
                    <!-- Resumen del Vehículo y Cliente -->
                    <div class="p-3.5 bg-gray-50 dark:bg-gray-900/70 rounded-xl border border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-600 dark:text-emerald-400 font-black flex items-center justify-center text-sm">
                                🚗
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-mono font-black text-sm text-gray-900 dark:text-white bg-gray-200/80 dark:bg-gray-700 px-2 py-0.5 rounded">
                                        {{ $deliveryOrder->vehicle?->plate }}
                                    </span>
                                    <span class="text-xs font-bold text-gray-800 dark:text-gray-200">
                                        {{ $deliveryOrder->vehicle?->brand }} {{ $deliveryOrder->vehicle?->model }}
                                    </span>
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-1.5">
                                    <span>👤</span>
                                    <span>Cliente: <strong class="text-gray-800 dark:text-gray-200">{{ $deliveryOrder->vehicle?->owner_name ?? 'Particular' }}</strong></span>
                                    @if ($deliveryOrder->vehicle?->owner_phone)
                                        <span class="text-gray-400">({{ $deliveryOrder->vehicle?->owner_phone }})</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="text-right">
                            <span class="text-[10px] text-gray-400 uppercase font-black tracking-wider block">Orden</span>
                            <span class="font-black text-base text-gray-900 dark:text-white">#{{ $deliveryOrder->id }}</span>
                        </div>
                    </div>

                    <!-- Estado del Pago y Advertencias -->
                    @if ($deliveryOrder->payment_status !== 'pagado')
                        @php
                            $pending = $deliveryOrder->pendingPaymentAmount();
                        @endphp
                        <div class="p-3.5 bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/70 rounded-xl text-xs flex flex-col gap-2.5">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2 text-amber-800 dark:text-amber-300 font-bold">
                                    <span class="text-base">⚠️</span>
                                    <span>Saldo pendiente por cobrar</span>
                                </div>
                                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-amber-800 dark:text-amber-200 bg-amber-200/90 dark:bg-amber-900 px-2.5 py-0.5 rounded-full border border-amber-300 dark:border-amber-700 uppercase">
                                    {{ $deliveryOrder->payment_status }}
                                </span>
                            </div>
                            <div class="flex items-baseline justify-between text-xs pt-1 border-t border-amber-200/70 dark:border-amber-800/50">
                                <span class="text-gray-600 dark:text-gray-400">Total cotizado: <strong class="text-gray-900 dark:text-white">@currency($deliveryOrder->total_amount ?? 0)</strong></span>
                                <span class="text-sm font-black text-rose-600 dark:text-rose-400 font-mono">Saldo pendiente: @currency($pending)</span>
                            </div>
                            <div class="pt-1 flex items-center justify-between">
                                <span class="text-[11px] text-amber-800 dark:text-amber-300">¿Deseas registrar el cobro ahora?</span>
                                <button type="button" wire:click="openPaymentModal({{ $deliveryOrder->id }})" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold text-xs shadow-sm transition">
                                    💳 Registrar Pago Ahora
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="p-3.5 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/70 rounded-xl text-xs flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold text-sm">
                                    ✓
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900 dark:text-white text-sm">Pago completado al 100%</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Total liquidado: <span class="font-bold text-emerald-600 dark:text-emerald-400 font-mono">@currency($deliveryOrder->total_amount ?? 0)</span></div>
                                </div>
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-emerald-600 text-white shadow-sm tracking-wide">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                Pagado
                            </span>
                        </div>
                    @endif

                    <!-- Nota Informativa con Alto Contraste en Modo Oscuro -->
                    <div class="p-3.5 bg-gray-100 dark:bg-gray-800/80 rounded-xl border border-gray-200 dark:border-gray-700 text-xs text-gray-700 dark:text-gray-300 flex items-start gap-3">
                        <div class="w-6 h-6 rounded-lg bg-blue-100 dark:bg-blue-900/60 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 text-xs font-bold mt-0.5">
                            ℹ️
                        </div>
                        <p class="leading-relaxed">
                            Al confirmar, la orden pasará a estado <span class="font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-950/80 border border-emerald-300 dark:border-emerald-800/80 px-1.5 py-0.5 rounded">Entregado</span> y se archivará del tablero Kanban. Podrás consultarla cuando quieras en <strong class="text-gray-900 dark:text-white font-semibold">"Historial de Entregados"</strong>.
                        </p>
                    </div>
                </div>
            @endif
        </x-slot>

        <x-slot name="footer">
            <button type="button"
                    wire:click="$set('showDeliveryModal', false)"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-xl text-xs font-bold transition">
                Cancelar
            </button>

            <button type="button"
                    wire:click="confirmDelivery"
                    wire:loading.attr="disabled"
                    class="ms-3 inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-black rounded-xl shadow-md transition disabled:opacity-50">
                <span wire:loading.remove wire:target="confirmDelivery">🔑 Sí, Entregar Vehículo</span>
                <span wire:loading wire:target="confirmDelivery">Procesando entrega...</span>
            </button>
        </x-slot>
    </x-dialog-modal>

    <!-- Modal de Historial de Vehículos Entregados -->
    <x-dialog-modal wire:model.live="showDeliveredModal" maxWidth="4xl">
        <x-slot name="title">
            <div class="flex items-center gap-2">
                <span class="p-2 bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 rounded-lg">
                    📦
                </span>
                <span class="font-bold text-gray-900 dark:text-white">Historial de Vehículos Entregados</span>
            </div>
        </x-slot>

        <x-slot name="content">
            <div class="max-h-[60vh] overflow-y-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800/80 border-b border-gray-200 dark:border-gray-700 text-xs font-bold uppercase text-gray-400">
                            <th class="p-3">Vehículo</th>
                            <th class="p-3">Cliente</th>
                            <th class="p-3">Fecha de Entrega</th>
                            <th class="p-3">Total / Pago</th>
                            <th class="p-3 text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-xs">
                        @forelse ($deliveredOrders as $dOrder)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                <td class="p-3">
                                    <span class="font-mono font-bold text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded">
                                        {{ $dOrder->vehicle?->plate }}
                                    </span>
                                    <span class="block text-gray-500 dark:text-gray-400 mt-0.5">
                                        {{ $dOrder->vehicle?->brand }} {{ $dOrder->vehicle?->model }}
                                    </span>
                                </td>
                                <td class="p-3">
                                    <span class="font-bold text-gray-800 dark:text-gray-200">{{ $dOrder->vehicle?->owner_name ?? 'N/D' }}</span>
                                    @if ($dOrder->vehicle?->owner_phone)
                                        <span class="block text-gray-400 text-[11px]">{{ $dOrder->vehicle?->owner_phone }}</span>
                                    @endif
                                </td>
                                <td class="p-3 text-gray-500 dark:text-gray-400 font-mono">
                                    {{ $dOrder->delivered_at ? $dOrder->delivered_at->format('d/m/Y H:i') : 'N/D' }}
                                </td>
                                <td class="p-3">
                                    <span class="font-mono font-bold text-gray-900 dark:text-white">
                                        {{ $dOrder->total_amount ? \App\Helpers\CurrencyHelper::formatDual($dOrder->total_amount) : 'Q 0.00' }}
                                    </span>
                                    <span class="block text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold">
                                        Pagado: @currencyDual($dOrder->paid_amount ?? 0)
                                    </span>
                                </td>
                                <td class="p-3 text-center">
                                    <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                                        Entregado
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-gray-400 italic">
                                    Aún no hay vehículos marcados como entregados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($deliveredOrders && method_exists($deliveredOrders, 'hasPages') && $deliveredOrders->hasPages())
                <div class="mt-4 px-2">
                    {{ $deliveredOrders->links() }}
                </div>
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('showDeliveredModal', false)">
                Cerrar
            </x-secondary-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Modal Crear Nueva Área -->
    <x-dialog-modal wire:model.live="showCreateAreaModal">
        <x-slot name="title">
            Nueva Área de Taller
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <div>
                    <x-label for="newAreaName" value="Nombre del Área *" class="text-xs font-semibold" />
                    <x-input id="newAreaName" type="text" class="mt-1 block w-full text-sm" wire:model="newAreaName" placeholder="Ej: Entrega de Vehículos o Mecánica Rápida" />
                    <x-input-error for="newAreaName" class="mt-1 text-xs" />
                </div>

                <div>
                    <x-label for="newAreaStatus" value="Estado que asigna automáticamente al mover la tarjeta *" class="text-xs font-semibold" />
                    <select id="newAreaStatus" wire:model="newAreaStatus" class="mt-1 block w-full text-sm rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                        @foreach ($statusOptions as $sKey => $sLabel)
                            <option value="{{ $sKey }}">{{ $sLabel }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2 pt-2 border-t border-gray-200 dark:border-gray-700">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Permisos y Funcionalidades del Área</span>

                    <!-- Generar Cotización -->
                    <div class="flex items-start gap-2.5 p-2.5 bg-amber-50 dark:bg-amber-950/20 rounded-xl border border-amber-200 dark:border-amber-800/60">
                        <input type="checkbox" id="newAreaCanGenerateQuotation" wire:model="newAreaCanGenerateQuotation" class="mt-0.5 rounded text-amber-600 focus:ring-amber-500 w-4 h-4">
                        <label for="newAreaCanGenerateQuotation" class="text-xs text-amber-900 dark:text-amber-200 cursor-pointer select-none">
                            <span class="font-bold block">💰 Generar / Editar Cotización de Trabajo</span>
                            <span class="text-[11px] text-amber-700 dark:text-amber-400">Permite establecer o modificar el monto presupuestado del trabajo en esta área.</span>
                        </label>
                    </div>

                    <!-- Mostrar Montos -->
                    <div class="flex items-start gap-2.5 p-2.5 bg-blue-50 dark:bg-blue-950/20 rounded-xl border border-blue-200 dark:border-blue-800/60">
                        <input type="checkbox" id="newAreaCanViewQuotationAmount" wire:model="newAreaCanViewQuotationAmount" class="mt-0.5 rounded text-blue-600 focus:ring-blue-500 w-4 h-4">
                        <label for="newAreaCanViewQuotationAmount" class="text-xs text-blue-900 dark:text-blue-200 cursor-pointer select-none">
                            <span class="font-bold block">👁️ Mostrar Montos de Cotización al Personal</span>
                            <span class="text-[11px] text-blue-700 dark:text-blue-400">Si se desmarca, los operarios en esta área solo verán el trabajo técnico sin cifras de dinero.</span>
                        </label>
                    </div>

                    <!-- Permitir Cobrar -->
                    <div class="flex items-start gap-2.5 p-2.5 bg-indigo-50 dark:bg-indigo-950/20 rounded-xl border border-indigo-200 dark:border-indigo-800/60">
                        <input type="checkbox" id="newAreaCanCollectPayment" wire:model="newAreaCanCollectPayment" class="mt-0.5 rounded text-indigo-600 focus:ring-indigo-500 w-4 h-4">
                        <label for="newAreaCanCollectPayment" class="text-xs text-indigo-900 dark:text-indigo-200 cursor-pointer select-none">
                            <span class="font-bold block">💳 Permitir Cobrar / Registrar Pagos</span>
                            <span class="text-[11px] text-indigo-700 dark:text-indigo-400">Habilita el botón de cobro y registro de abonos para los vehículos en esta área.</span>
                        </label>
                    </div>

                    <!-- Entrega de Vehículo -->
                    <div class="flex items-start gap-2.5 p-2.5 bg-emerald-50 dark:bg-emerald-950/30 rounded-xl border border-emerald-200 dark:border-emerald-800">
                        <input type="checkbox" id="newAreaIsDelivery" wire:model="newAreaIsDelivery" class="mt-0.5 rounded text-emerald-600 focus:ring-emerald-500 w-4 h-4">
                        <label for="newAreaIsDelivery" class="text-xs text-emerald-900 dark:text-emerald-200 cursor-pointer select-none">
                            <span class="font-bold block">🔑 Área de Entrega de Vehículo</span>
                            <span class="text-[11px] text-emerald-700 dark:text-emerald-400">Permite dar por entregado el vehículo al cliente y archivarlo del Kanban.</span>
                        </label>
                    </div>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('showCreateAreaModal', false)">
                Cancelar
            </x-secondary-button>

            <x-button class="ms-3 bg-indigo-600 hover:bg-indigo-700" wire:click="createArea">
                Crear Área
            </x-button>
        </x-slot>
    </x-dialog-modal>

</div>
