<div class="h-screen flex flex-col">
    <div class="px-4 py-4 bg-white border-b border-gray-200 flex justify-between items-center">
        <h1 class="text-xl font-bold text-gray-800">Tablero de Taller</h1>
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
    <div class="flex-1 overflow-x-auto overflow-y-hidden bg-gray-100 p-6">
        <div class="flex h-full space-x-4">
            
            @foreach($areas as $area)
                <div class="flex-shrink-0 w-80 bg-gray-200 rounded-lg flex flex-col max-h-full"
                    x-data
                    @dragover.prevent
                    @drop.prevent="
                        let orderId = $event.dataTransfer.getData('orderId');
                        $wire.updateOrderArea(orderId, {{ $area->id }});
                    "
                >
                    <!-- Column Header -->
                    <div class="p-3 bg-gray-300 rounded-t-lg font-bold text-gray-700 flex justify-between items-center cursor-move">
                        <span>{{ $area->name }}</span>
                        <span class="bg-gray-400 text-white text-xs px-2 py-1 rounded-full">{{ $area->workOrders->count() }}</span>
                    </div>

                    <!-- Cards Container -->
                    <div class="flex-1 overflow-y-auto p-3 space-y-3">
                        @foreach($area->workOrders as $order)
                            <div class="bg-white p-4 rounded shadow hover:shadow-md cursor-grab active:cursor-grabbing border-l-4 border-indigo-500"
                                 draggable="true"
                                 @dragstart="$event.dataTransfer.setData('orderId', {{ $order->id }})"
                                 wire:click="selectOrder({{ $order->id }})"
                            >
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-indigo-700 font-bold text-sm">{{ $order->vehicle->plate }}</span>
                                    <span class="text-gray-400 text-xs text-right">{{ $order->created_at->diffForHumans() }}</span>
                                </div>
                                
                                <div class="text-sm font-medium text-gray-800 mb-1">
                                    {{ $order->vehicle->brand }} {{ $order->vehicle->model }}
                                </div>
                                <div class="text-xs text-gray-500 mb-2">
                                    {{ $order->vehicle->color }} - {{ $order->vehicle->owner_name }}
                                </div>

                                <div class="flex justify-between items-center mt-3 pt-2 border-t border-gray-100">
                                     <span class="text-xs px-2 py-0.5 rounded bg-gray-100 text-gray-600">
                                        #{{ $order->id }}
                                    </span>
                                    
                                     <!-- Status Indicator -->
                                     @php
                                        $statusColors = [
                                            'recepcion' => 'bg-blue-100 text-blue-800',
                                            'presupuesto' => 'bg-yellow-100 text-yellow-800',
                                            'autorizado' => 'bg-green-100 text-green-800',
                                            'en_proceso' => 'bg-indigo-100 text-indigo-800',
                                            'finalizado' => 'bg-purple-100 text-purple-800',
                                        ];
                                        $color = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800';
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
                        <p class="text-xs text-gray-500">{{ $selectedOrder->vehicle->owner_phone }}</p>
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
</div>
