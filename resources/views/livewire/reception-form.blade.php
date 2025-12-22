<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        
        <!-- Progress Bar -->
        <div class="bg-gray-200 h-2 w-full">
            <div class="bg-indigo-600 h-2 transition-all duration-300 ease-in-out" 
                 style="width: {{ ($currentStep / $totalSteps) * 100 }}%">
            </div>
        </div>

        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
            
            <!-- Step Indicators -->
            <div class="flex justify-between mb-8">
                <div class="text-xs {{ $currentStep >= 1 ? 'text-indigo-600 font-bold' : 'text-gray-400' }}">1. Vehículo y Cliente</div>
                <div class="text-xs {{ $currentStep >= 2 ? 'text-indigo-600 font-bold' : 'text-gray-400' }}">2. Estado de Recepción</div>
                <div class="text-xs {{ $currentStep >= 3 ? 'text-indigo-600 font-bold' : 'text-gray-400' }}">3. Daños y Fotos</div>
                <div class="text-xs {{ $currentStep >= 4 ? 'text-indigo-600 font-bold' : 'text-gray-400' }}">4. Confirmación</div>
            </div>

            <form wire:submit.prevent="submit">
                
                <!-- STEP 1: VEHICLE & CLIENT -->
                @if($currentStep === 1)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Search Section -->
                        <div class="col-span-1 md:col-span-2">
                            <label class="block font-medium text-sm text-gray-700">Buscar Placa</label>
                            <div class="flex gap-2">
                                <input wire:model="search_plate" type="text" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full uppercase" placeholder="ABC-123">
                                <button wire:click.prevent="searchVehicle" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Buscar</button>
                            </div>
                            @error('search_plate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Vehicle Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Datos del Vehículo</h3>
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Placa</label>
                                    <input wire:model="plate" type="text" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full uppercase">
                                    @error('plate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="block font-medium text-sm text-gray-700">Marca</label>
                                        <input wire:model="brand" type="text" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                                    </div>
                                    <div>
                                        <label class="block font-medium text-sm text-gray-700">Modelo</label>
                                        <input wire:model="model" type="text" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                                    </div>
                                </div>
                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Línea</label>
                                    <input wire:model="line" type="text" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="block font-medium text-sm text-gray-700">Color</label>
                                        <input wire:model="color" type="text" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                                    </div>
                                    <div>
                                        <label class="block font-medium text-sm text-gray-700">Año</label>
                                        <input wire:model="year" type="number" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                                    </div>
                                </div>
                                <div>
                                    <label class="block font-medium text-sm text-gray-700">VIN</label>
                                    <input wire:model="vin" type="text" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full uppercase">
                                </div>
                            </div>
                        </div>

                        <!-- Owner & Reception Info -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Datos del Cliente</h3>
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Nombre</label>
                                    <input wire:model="owner_name" type="text" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                                    @error('owner_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="block font-medium text-sm text-gray-700">Teléfono</label>
                                        <input wire:model="owner_phone" type="text" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                                    </div>
                                    <div>
                                        <label class="block font-medium text-sm text-gray-700">Email</label>
                                        <input wire:model="owner_email" type="email" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                                    </div>
                                </div>
                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Dirección</label>
                                    <input wire:model="owner_address" type="text" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                                </div>
                            </div>
                            
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4 mt-6">Estado Inicial</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Kilometraje</label>
                                    <input wire:model="mileage" type="number" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                                    @error('mileage') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Combustible</label>
                                    <select wire:model="fuel_level" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                                        <option value="">Seleccione</option>
                                        <option value="E">Vacío (E)</option>
                                        <option value="1/4">1/4</option>
                                        <option value="1/2">1/2</option>
                                        <option value="3/4">3/4</option>
                                        <option value="F">Lleno (F)</option>
                                    </select>
                                    @error('fuel_level') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                    </div>
                @endif

                <!-- STEP 2: CHECKLIST -->
                @if($currentStep === 2)
                    <div class="space-y-6">
                        <h3 class="text-lg font-medium text-gray-900">Listado de Recepción</h3>
                        <p class="text-sm text-gray-500">Marque el estado de cada componente.</p>

                        @if(empty($checklistItems))
                            <div class="bg-yellow-100 p-4 rounded text-yellow-800">No hay plantilla de checklist configurada.</div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($checklistItems as $section => $items)
                                    <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                                        <h4 class="font-bold text-gray-700 mb-3 border-b">{{ $section }}</h4>
                                        <div class="space-y-3">
                                            @foreach($items as $item => $status)
                                                <div class="flex justify-between items-center text-sm">
                                                    <span class="text-gray-600">{{ $item }}</span>
                                                    <div class="flex space-x-1">
                                                        <!-- Buttons act as radio inputs -->
                                                        <button type="button" 
                                                            wire:click="$set('checklistItems.{{ $section }}.{{ $item }}', 'correct')"
                                                            class="w-6 h-6 rounded-full flex items-center justify-center border {{ $status === 'correct' ? 'bg-green-500 border-green-600 text-white' : 'bg-white border-gray-300 text-gray-400 hover:bg-gray-100' }}" 
                                                            title="Correcto">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                        </button>
                                                        
                                                        <button type="button" 
                                                            wire:click="$set('checklistItems.{{ $section }}.{{ $item }}', 'wear')"
                                                            class="w-6 h-6 rounded-full flex items-center justify-center border {{ $status === 'wear' ? 'bg-yellow-500 border-yellow-600 text-white' : 'bg-white border-gray-300 text-gray-400 hover:bg-gray-100' }}" 
                                                            title="Desgaste">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                        </button>

                                                        <button type="button" 
                                                            wire:click="$set('checklistItems.{{ $section }}.{{ $item }}', 'missing')"
                                                            class="w-6 h-6 rounded-full flex items-center justify-center border {{ $status === 'missing' ? 'bg-red-500 border-red-600 text-white' : 'bg-white border-gray-300 text-gray-400 hover:bg-gray-100' }}" 
                                                            title="Falta/Malo">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
                
                <!-- STEP 3: DAMAGES & PHOTOS -->
                @if($currentStep === 3)
                     <!-- Placeholder for Image Mapper -->
                    <div class="space-y-6 text-center py-10">
                        <svg class="mx-auto h-24 w-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Diagrama de Daños</h3>
                        <p class="mt-1 text-sm text-gray-500">Aquí se mostrará el diagrama del vehículo para marcar daños (Implementación pendiente de gráficos).</p>
                    </div>

                    <div class="border-t pt-6">
                        <label class="block font-medium text-sm text-gray-700 mb-2">Fotografías del Vehículo</label>
                        <div class="flex items-center justify-center w-full">
                            <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click para subir</span> o arrastrar y soltar</p>
                                    <p class="text-xs text-gray-500">PNG, JPG (MAX. 5MB)</p>
                                </div>
                                <input id="dropzone-file" type="file" wire:model="photos" multiple class="hidden" />
                            </label>
                        </div>
                        @if ($photos)
                            <div class="flex gap-2 mt-4 overflow-x-auto">
                                @foreach($photos as $photo)
                                    <img src="{{ $photo->temporaryUrl() }}" class="h-20 w-20 object-cover rounded shadow">
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
                
                <!-- STEP 4: REVIEW -->
                @if($currentStep === 4)
                    <div class="space-y-6">
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Resumen de Recepción</h3>
                            
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Vehículo</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $brand }} {{ $model }} {{ $year }} - {{ $plate }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Cliente</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $owner_name }} ({{ $owner_phone }})</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Kilometraje</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ number_format($mileage) }} km</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Combustible</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $fuel_level }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                @endif

                <!-- Navigation Buttons -->
                <div class="flex items-center justify-between mt-8 border-t pt-4">
                    
                    @if($currentStep > 1)
                        <button type="button" wire:click="previousStep" class="bg-gray-200 text-gray-700 px-4 py-2 rounded shadow-sm hover:bg-gray-300">
                            Atrás
                        </button>
                    @else
                        <div></div>
                    @endif

                    @if($currentStep < $totalSteps)
                        <button type="button" wire:click="nextStep" class="bg-indigo-600 text-white px-4 py-2 rounded shadow-sm hover:bg-indigo-700">
                            Siguiente
                        </button>
                    @else
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded shadow-sm hover:bg-indigo-700 font-bold">
                            Crear Orden de Recepción
                        </button>
                    @endif
                </div>

            </form>
        </div>
    </div>
</div>
