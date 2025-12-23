<div class="h-screen flex flex-col" x-data>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
    <div class="px-4 py-4 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
        <h1 class="text-xl font-bold text-gray-800 dark:text-white">Tablero de Taller</h1>
        <div class="flex space-x-2">
             <a href="{{ route('reception.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium py-2 px-4 rounded-md flex items-center">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nueva Recepción
            </a>
        </div>
    </div>

    <!-- Kanban Board Container -->
    <div 
        class="flex-1 overflow-x-auto overflow-y-hidden bg-gray-100 dark:bg-gray-900 p-6" 
        x-data="{ 
            initBoard() {
                // Initialize Horizontal Sortable for Columns (Areas)
                new Sortable(this.$refs.board, {
                    animation: 150,
                    handle: '.column-header', // Drag via header only
                    ghostClass: 'opacity-50',
                    onEnd: (evt) => {
                        let orderedIds = Array.from(evt.to.children).map(el => el.dataset.areaId);
                        $wire.updateAreaOrders(orderedIds);
                    }
                });
            },
            initColumn(el, areaId) {
                // Initialize Vertical Sortable for Cards (Orders)
                new Sortable(el, {
                    group: 'shared', // Allow dragging between columns
                    animation: 150,
                    ghostClass: 'bg-indigo-50',
                    onEnd: (evt) => {
                        // Logic for moving/sorting
                        // If moved to another list, the 'onAdd' of the target list fires? 
                        // Actually 'onEnd' fires for the source list. 
                        // But verifying: we just want to update the relationship if moved to another column.
                        
                        let itemEl = evt.item;
                        let newAreaId = evt.to.dataset.areaId;
                        let oldAreaId = evt.from.dataset.areaId;
                        let orderId = itemEl.dataset.orderId;

                        if (newAreaId !== oldAreaId) {
                            $wire.updateOrderArea(orderId, newAreaId);
                        }
                    }
                });
            }
        }"
        x-init="initBoard()"
    >
        <div class="flex h-full space-x-4" x-ref="board">
            
            @foreach($areas as $index => $area)
                <div 
                    class="flex-shrink-0 w-80 bg-gray-200 dark:bg-gray-800 rounded-lg flex flex-col max-h-full transition-opacity duration-200"
                    data-area-id="{{ $area->id }}"
                >
                    <!-- Column Header -->
                    <div class="column-header p-3 bg-gray-300 dark:bg-gray-700 rounded-t-lg font-bold text-gray-700 dark:text-gray-200 flex justify-between items-center cursor-grab active:cursor-grabbing">
                        <span>{{ $area->name }}</span>
                        <div class="flex items-center">
                            <span class="bg-gray-400 text-white text-xs px-2 py-1 rounded-full mr-2">{{ $area->workOrders->count() }}</span>
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </div>
                    </div>

                    <!-- Cards Container -->
                    <div 
                        class="flex-1 overflow-y-auto p-3 space-y-3"
                        data-area-id="{{ $area->id }}"
                        x-init="initColumn($el, {{ $area->id }})"
                    >
                        @foreach($area->workOrders as $order)
                            <div class="bg-white dark:bg-gray-700 p-4 rounded shadow hover:shadow-md cursor-grab active:cursor-grabbing border-l-4 border-indigo-500"
                                 data-order-id="{{ $order->id }}"
                                 wire:click="selectOrder({{ $order->id }})"
                            >
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-indigo-700 dark:text-indigo-300 font-bold text-sm">{{ $order->vehicle->plate }}</span>
                                    <span class="text-gray-400 dark:text-gray-500 text-xs text-right">{{ $order->created_at->diffForHumans() }}</span>
                                </div>
                                
                                <div class="text-sm font-medium text-gray-800 dark:text-gray-200 mb-1">
                                    {{ $order->vehicle->brand }} {{ $order->vehicle->model }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                                    {{ $order->vehicle->color }} - {{ $order->vehicle->owner_name }}
                                </div>

                                <div class="flex justify-between items-center mt-3 pt-2 border-t border-gray-100 dark:border-gray-600">
                                     <span class="text-xs px-2 py-0.5 rounded bg-gray-100 dark:bg-gray-600 text-gray-600 dark:text-gray-300">
                                        #{{ $order->id }}
                                    </span>
                                    
                                     <!-- Status Indicator -->
                                     @php
                                        $statusColors = [
                                            'recibido' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                            'presupuesto' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                            'en_espera' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
                                            'trabajando' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300',
                                            'revision' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
                                            'terminado' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                        ];
                                        $color = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                                     @endphp
                                     <span class="text-xs px-2 py-0.5 rounded {{ $color }}">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                        
                        @if($area->workOrders->isEmpty())
                            <div class="text-center py-6 text-gray-400 text-sm italic">
                                Sin órdenes
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach

            <!-- Add Area Button -->
             <div class="flex-shrink-0 w-12 pt-2">
                <button wire:click="$set('showCreateAreaModal', true)" class="w-10 h-10 rounded-full bg-green-500 hover:bg-green-600 text-white flex items-center justify-center shadow-lg transition-colors" title="Agregar Nueva Área">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- Order Detail Modal -->
    <x-dialog-modal wire:model="showOrderModal">
        <x-slot name="title">
            @if($selectedOrder)
                Orden #{{ $selectedOrder->id }} - {{ $selectedOrder->vehicle->plate ?? 'N/A' }}
            @endif
        </x-slot>

        <x-slot name="content">
            @if($selectedOrder)
                 <div class="grid grid-cols-2 gap-4">
                    <div>
                        <h4 class="font-bold text-gray-700 text-sm">Vehículo</h4>
                        <p class="text-sm text-gray-600">{{ $selectedOrder->vehicle->brand }} {{ $selectedOrder->vehicle->model }}</p>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-700 text-sm">Cliente</h4>
                        <p class="text-sm text-gray-600">{{ $selectedOrder->vehicle->owner_name }}</p>
                        @if(Auth::user()->can_view_contact_info)
                            <p class="text-xs text-gray-500">{{ $selectedOrder->vehicle->owner_phone }}</p>
                        @else
                            <p class="text-xs text-gray-400 italic">Contacto Oculto</p>
                        @endif
                    </div>
                 </div>

                 <div class="mt-4 border-t pt-4">
                    <h4 class="font-bold text-gray-700 text-sm mb-2">Checklist Inicial</h4>
                    <div class="h-40 overflow-y-auto">
                        <ul class="text-sm space-y-1">
                            @foreach($selectedOrder->checklists as $check)
                                @if($check->status != 'correct')
                                    <li class="flex items-center text-red-600">
                                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                        {{ $check->group_name }} - {{ $check->item_name }}: {{ $check->status }}
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                         @if($selectedOrder->checklists->where('status', '!=', 'correct')->isEmpty())
                            <p class="text-green-600 text-sm">Todo reportado correcto.</p>
                        @endif
                    </div>
                 </div>
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('showOrderModal', false)" wire:loading.attr="disabled">
                Cerrar
            </x-secondary-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Create Area Modal -->
    <x-dialog-modal wire:model="showCreateAreaModal">
        <x-slot name="title">
            Nueva Área de Taller
        </x-slot>
        <x-slot name="content">
            <div class="col-span-6 sm:col-span-4">
                <x-label for="newAreaName" value="{{ __('Nombre del Área') }}" />
                <x-input id="newAreaName" type="text" class="mt-1 block w-full" wire:model="newAreaName" placeholder="Ej: Mecánica Rápida" />
                <x-input-error for="newAreaName" class="mt-2" />
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="$set('showCreateAreaModal', false)" wire:loading.attr="disabled">
                Cancelar
            </x-secondary-button>

            <x-button class="ml-2" wire:click="createArea" wire:loading.attr="disabled">
                Crear Área
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
