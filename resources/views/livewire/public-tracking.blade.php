<div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex flex-col items-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
           <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 dark:text-white">
                Rastreo de Vehículo
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
                Ingrese el número de placa para consultar el estado.
            </p>
        </div>
        
        <form wire:submit.prevent="search" class="mt-8 space-y-6">
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="plate" class="sr-only">Placa</label>
                    <input wire:model="plate" id="plate" name="plate" type="text" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm uppercase dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:placeholder-gray-500" placeholder="Ej. ABC-123">
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    Buscar
                </button>
            </div>
        </form>

        @if($searched)
            <div class="mt-10 bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                @if($workOrder)
                     <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            {{ $workOrder->vehicle->brand }} {{ $workOrder->vehicle->model }}
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                            Placa: {{ $workOrder->vehicle->plate }}
                        </p>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-5 sm:p-0">
                        <dl class="sm:divide-y sm:divide-gray-200 dark:divide-gray-700">
                            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Estado Actual</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">
                                     <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ ucfirst(str_replace('_', ' ', $workOrder->status)) }}
                                    </span>
                                </dd>
                            </div>
                             <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Ubicación</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">
                                    {{ $workOrder->area->name ?? 'N/A' }}
                                </dd>
                            </div>
                            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Ingreso</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">
                                    {{ $workOrder->created_at->format('d/m/Y H:i') }}
                                </dd>
                            </div>
                        </dl>
                        
                        <!-- Simple Timeline -->
                        <div class="p-6">
                            <h4 class="font-bold text-gray-700 dark:text-gray-300 mb-4">Progreso</h4>
                            <div class="relative">
                                @php
                                    $steps = ['recepcion', 'presupuesto', 'autorizado', 'en_proceso', 'finalizado'];
                                    $currentStatusIndex = array_search($workOrder->status, $steps);
                                    if ($currentStatusIndex === false && $workOrder->status == 'entregado') $currentStatusIndex = 5;
                                @endphp
                                
                                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                    <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                                </div>
                                <div class="relative flex justify-between">
                                    @foreach($steps as $index => $step)
                                        <div class="flex flex-col items-center">
                                            <div class="bg-white dark:bg-gray-800 px-2">
                                                <div class="h-4 w-4 rounded-full border-2 {{ $index <= $currentStatusIndex ? 'bg-indigo-600 border-indigo-600' : 'border-gray-300 dark:border-gray-600' }}"></div>
                                            </div>
                                            <div class="mt-2 text-xs text-gray-500 dark:text-gray-400 capitalize">{{ str_replace('_', ' ', $step) }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    </div>
                @else
                    <div class="px-4 py-5 sm:p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No encontrado</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">No encontramos una orden activa para la placa {{ $plate }}.</p>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
