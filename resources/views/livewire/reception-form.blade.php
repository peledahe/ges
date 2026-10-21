<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
        
        <!-- Progress Bar -->
        <div class="bg-gray-200 dark:bg-gray-700 h-2 w-full">
            <div class="bg-indigo-600 h-2 transition-all duration-300 ease-in-out" 
                 style="width: {{ ($currentStep / $totalSteps) * 100 }}%">
            </div>
        </div>

        <div class="p-6 sm:px-20 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
            
            <!-- Step Indicators -->
            <div class="flex justify-between mb-8">
                <div class="text-xs {{ $currentStep >= 1 ? 'text-indigo-600 dark:text-indigo-400 font-bold' : 'text-gray-400 dark:text-gray-500' }}">1. Vehículo y Cliente</div>
                <div class="text-xs {{ $currentStep >= 2 ? 'text-indigo-600 dark:text-indigo-400 font-bold' : 'text-gray-400 dark:text-gray-500' }}">2. Estado de Recepción</div>
                <div class="text-xs {{ $currentStep >= 3 ? 'text-indigo-600 dark:text-indigo-400 font-bold' : 'text-gray-400 dark:text-gray-500' }}">3. Daños y Fotos</div>
                <div class="text-xs {{ $currentStep >= 4 ? 'text-indigo-600 dark:text-indigo-400 font-bold' : 'text-gray-400 dark:text-gray-500' }}">4. Confirmación</div>
            </div>

            <form wire:submit.prevent="submit">
                
                <!-- STEP 1: CLIENT & VEHICLE -->
                @if($currentStep === 1)
                    <div class="space-y-8">
                        
                        <!-- SECCIÓN 1: DATOS DEL CLIENTE (Primero) -->
                        <div class="bg-indigo-50/50 dark:bg-gray-800/80 p-5 rounded-xl border border-indigo-100 dark:border-gray-700 shadow-sm">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 mb-4 border-b border-indigo-100 dark:border-gray-700 gap-2">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        1. Datos del Cliente
                                    </h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Identifica o registra al propietario por teléfono, correo o NIT para rastrear su historial.</p>
                                </div>

                                @if($is_existing_customer)
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-900/60 text-emerald-800 dark:text-emerald-300">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                            Cliente Existente
                                        </span>
                                        <button type="button" wire:click="resetCustomer" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">
                                            Cambiar / Nuevo
                                        </button>
                                    </div>
                                @endif
                            </div>

                            <!-- Barra de búsqueda rápida de cliente -->
                            <div class="mb-4">
                                <label class="block font-medium text-xs text-gray-700 dark:text-gray-300 mb-1">Buscar Cliente (Teléfono, Correo, NIT o Nombre)</label>
                                <div class="flex gap-2">
                                    <div class="relative flex-1">
                                        <input wire:model="search_customer" 
                                               wire:keydown.enter.prevent="searchCustomer"
                                               type="text" 
                                               class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm w-full text-sm pl-9" 
                                               placeholder="Ej. 59696181, cliente@correo.com, 123456-7...">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                        </div>
                                    </div>
                                    <button type="button" wire:click.prevent="searchCustomer" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition flex items-center gap-1.5 shadow-sm">
                                        Buscar
                                    </button>
                                </div>
                                @error('search_customer') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Múltiples resultados encontrados -->
                            @if(!empty($customer_search_results))
                                <div class="mb-4 bg-white dark:bg-gray-900 border border-indigo-200 dark:border-indigo-900/50 rounded-lg p-3 shadow-sm">
                                    <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Selecciona un cliente de los resultados:</p>
                                    <div class="space-y-1.5 max-h-48 overflow-y-auto">
                                        @foreach($customer_search_results as $c)
                                            <div wire:click="selectCustomer({{ $c['id'] }})" class="p-2 rounded-md hover:bg-indigo-50 dark:hover:bg-gray-800 cursor-pointer flex items-center justify-between transition text-xs border border-transparent hover:border-indigo-200">
                                                <div>
                                                    <span class="font-bold text-gray-800 dark:text-white">{{ $c['name'] }}</span>
                                                    <span class="text-gray-500 dark:text-gray-400 ml-2">Tel: {{ $c['phone'] ?: 'N/D' }} | NIT: {{ $c['nit'] ?: 'C/F' }}</span>
                                                </div>
                                                <span class="text-indigo-600 dark:text-indigo-400 font-medium">Elegir &rarr;</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Vehículos registrados del cliente e historial -->
                            @if($is_existing_customer && !empty($customer_vehicles))
                                <div class="mb-4 bg-white/80 dark:bg-gray-900/80 border border-gray-200 dark:border-gray-700 rounded-lg p-3">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-bold text-gray-700 dark:text-gray-300">Vehículos del Cliente (Haz clic para cargar uno):</span>
                                        <span class="text-xs bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 px-2 py-0.5 rounded font-medium">
                                            {{ $customer_repair_count }} {{ $customer_repair_count === 1 ? 'reparación previa' : 'reparaciones previas' }}
                                        </span>
                                    </div>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($customer_vehicles as $cv)
                                            <button type="button" 
                                                    wire:click="selectCustomerVehicle({{ $cv['id'] }})"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium border transition {{ $vehicle_id == $cv['id'] ? 'bg-indigo-600 text-white border-indigo-600 shadow' : 'bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600 hover:bg-gray-100' }}">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
                                                <span class="font-bold">{{ $cv['plate'] }}</span> - {{ $cv['brand'] }} {{ $cv['model'] }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Campos del cliente -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="md:col-span-1">
                                    <label class="block font-medium text-xs text-gray-700 dark:text-gray-300">Nombre / Razón Social *</label>
                                    <input wire:model="owner_name" type="text" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm w-full text-sm mt-1">
                                    @error('owner_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block font-medium text-xs text-gray-700 dark:text-gray-300">Teléfono / Celular</label>
                                    <input wire:model="owner_phone" type="text" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm w-full text-sm mt-1" placeholder="Ej. 59696181">
                                </div>
                                <div>
                                    <label class="block font-medium text-xs text-gray-700 dark:text-gray-300">NIT / Identificación Fiscal</label>
                                    <input wire:model="owner_nit" type="text" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm w-full text-sm mt-1" placeholder="Ej. 1234567-8 o C/F">
                                </div>
                                <div>
                                    <label class="block font-medium text-xs text-gray-700 dark:text-gray-300">Correo Electrónico</label>
                                    <input wire:model="owner_email" type="email" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm w-full text-sm mt-1" placeholder="cliente@correo.com">
                                    @error('owner_email') <span class="text-rose-500 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block font-medium text-xs text-gray-700 dark:text-gray-300">Dirección</label>
                                    <input wire:model="owner_address" type="text" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm w-full text-sm mt-1" placeholder="Dirección completa">
                                </div>
                            </div>

                            <!-- Control Gráfico: Acceso al Portal de Cliente (Cliente Preferencial) -->
                            <div class="mt-5 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <div class="rounded-2xl border transition-all duration-300 overflow-hidden {{ $client_user_status === 'activo' ? 'bg-gradient-to-r from-emerald-500/10 via-indigo-500/5 to-transparent border-emerald-500/40 shadow-sm shadow-emerald-500/10' : 'bg-gray-50/70 dark:bg-gray-800/60 border-gray-200 dark:border-gray-700/80 hover:border-gray-300 dark:hover:border-gray-600' }}">
                                    
                                    <div class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                        <!-- Información Izquierda -->
                                        <div class="flex items-start gap-3.5 min-w-0 flex-1 cursor-pointer select-none" wire:click="toggleClientUserStatus">
                                            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 transition-all {{ $client_user_status === 'activo' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/25 ring-4 ring-emerald-500/20' : 'bg-indigo-100 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400' }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                            </div>
                                            
                                            <div class="min-w-0 flex-1">
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <span class="font-extrabold text-sm text-gray-900 dark:text-white">
                                                        Acceso al Portal de Cliente
                                                    </span>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold tracking-wider uppercase {{ $client_user_status === 'activo' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/60 dark:text-emerald-300' : 'bg-gray-200/80 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">
                                                        ⭐ Cliente Preferencial
                                                    </span>
                                                </div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-relaxed">
                                                    Permite al cliente ingresar con su correo al portal para consultar cotizaciones, órdenes y comprobantes de pago.
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Toggle Switch Gráfico Derecho -->
                                        <div class="flex items-center gap-3.5 flex-shrink-0 pl-13 sm:pl-0">
                                            <!-- Indicador de Texto -->
                                            <div class="text-left sm:text-right cursor-pointer select-none" wire:click="toggleClientUserStatus">
                                                <span class="block text-xs font-bold transition-colors {{ $client_user_status === 'activo' ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-500 dark:text-gray-400' }}">
                                                    {{ $client_user_status === 'activo' ? '● Portal Habilitado' : '○ Inactivo' }}
                                                </span>
                                                <span class="block text-[10px] text-gray-400 dark:text-gray-500">
                                                    {{ $client_user_status === 'activo' ? 'Generará credenciales' : 'Por defecto' }}
                                                </span>
                                            </div>

                                            <!-- Botón Switch Interactivo -->
                                            <button type="button"
                                                    wire:click="toggleClientUserStatus"
                                                    role="switch"
                                                    aria-checked="{{ $client_user_status === 'activo' ? 'true' : 'false' }}"
                                                    class="relative inline-flex h-7 w-13 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 {{ $client_user_status === 'activo' ? 'bg-emerald-500' : 'bg-gray-300 dark:bg-gray-700' }}">
                                                <span class="sr-only">Habilitar portal de cliente</span>
                                                <span aria-hidden="true"
                                                      class="pointer-events-none inline-block h-6 w-6 transform rounded-full bg-white shadow-md ring-0 transition duration-200 ease-in-out flex items-center justify-center text-[10px] font-bold {{ $client_user_status === 'activo' ? 'translate-x-6 text-emerald-600' : 'translate-x-0 text-gray-400' }}">
                                                    {{ $client_user_status === 'activo' ? '✓' : '✕' }}
                                                </span>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Banner Expandido cuando el portal está activo -->
                                    @if($client_user_status === 'activo')
                                        <div class="px-5 py-3 bg-emerald-50/90 dark:bg-emerald-950/40 border-t border-emerald-200/60 dark:border-emerald-800/60 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs">
                                            <div class="flex items-center gap-2 text-emerald-900 dark:text-emerald-200">
                                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse flex-shrink-0"></span>
                                                @if(!empty($owner_email))
                                                    <span>Usuario preferencial vinculado a: <strong class="font-mono font-bold">{{ $owner_email }}</strong></span>
                                                @else
                                                    <span class="text-amber-800 dark:text-amber-300 font-medium">⚠️ Recuerda ingresar el <strong>Correo Electrónico</strong> en el formulario para habilitar el acceso.</span>
                                                @endif
                                            </div>
                                            <span class="text-[11px] font-semibold text-emerald-700 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-900/60 px-2.5 py-0.5 rounded-full self-start sm:self-auto">
                                                Acceso: Portal de Clientes
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- SECCIÓN 2: DATOS DEL VEHÍCULO -->
                        <div class="bg-gray-50/50 dark:bg-gray-800/80 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                            <div class="flex items-center justify-between pb-3 mb-4 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
                                    2. Datos del Vehículo
                                </h3>
                                @if($vehicle_id)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-blue-100 dark:bg-blue-900/60 text-blue-800 dark:text-blue-300">
                                        Vehículo Registrado #{{ $vehicle_id }}
                                    </span>
                                @endif
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block font-medium text-xs text-gray-700 dark:text-gray-300">Tipo de Vehículo *</label>
                                    <select wire:model.live="type" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm w-full text-sm mt-1 h-10">
                                        <option value="">Seleccione</option>
                                        @foreach ($allowedVehicleTypes as $vKey => $vLabel)
                                            <option value="{{ $vKey }}">{{ $vLabel }}</option>
                                        @endforeach
                                    </select>
                                    @error('type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block font-medium text-xs text-gray-700 dark:text-gray-300">Placa *</label>
                                    <div class="flex gap-1.5 mt-1">
                                        <input wire:model="plate" 
                                               wire:keydown.enter.prevent="searchVehicle" 
                                               type="text" 
                                               class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm flex-1 uppercase text-sm h-10 px-3" 
                                               placeholder="P-000XXX">
                                        <button wire:click.prevent="searchVehicle" 
                                                type="button" 
                                                class="w-10 h-10 flex-shrink-0 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white rounded-lg flex items-center justify-center transition-colors shadow-sm"
                                                title="Buscar vehículo por placa">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    @error('plate') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    @error('search_plate') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block font-medium text-xs text-gray-700 dark:text-gray-300">Marca *</label>
                                    <input wire:model="brand" type="text" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm w-full text-sm mt-1 h-10" placeholder="Toyota, Honda, etc.">
                                    @error('brand') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block font-medium text-xs text-gray-700 dark:text-gray-300">Modelo *</label>
                                    <input wire:model="model" type="text" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm w-full text-sm mt-1 h-10" placeholder="Corolla, Civic, etc.">
                                    @error('model') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block font-medium text-xs text-gray-700 dark:text-gray-300">Línea</label>
                                    <input wire:model="line" type="text" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm w-full text-sm mt-1 h-10" placeholder="LE, XLE, Sport...">
                                </div>

                                <div>
                                    <label class="block font-medium text-xs text-gray-700 dark:text-gray-300">Color</label>
                                    <input wire:model="color" type="text" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm w-full text-sm mt-1 h-10" placeholder="Gris, Blanco...">
                                </div>

                                <div>
                                    <label class="block font-medium text-xs text-gray-700 dark:text-gray-300">Año</label>
                                    <input wire:model="year" type="number" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm w-full text-sm mt-1 h-10" placeholder="2020">
                                </div>

                                <div>
                                    <label class="block font-medium text-xs text-gray-700 dark:text-gray-300">
                                        Puertas {{ in_array($type, ['pasola', 'moto']) ? '(N/A)' : '*' }}
                                    </label>
                                    <input wire:model="doors_qty" 
                                           type="number" 
                                           min="{{ in_array($type, ['pasola', 'moto']) ? '0' : '2' }}" 
                                           max="5" 
                                           class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm w-full text-sm mt-1 h-10 {{ in_array($type, ['pasola', 'moto']) ? 'opacity-60 cursor-not-allowed' : '' }}"
                                           {{ in_array($type, ['pasola', 'moto']) ? 'readonly' : '' }}>
                                    @error('doors_qty') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block font-medium text-xs text-gray-700 dark:text-gray-300">Motorización / Energía *</label>
                                    <div class="grid grid-cols-3 gap-1 mt-1 h-10 p-1 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm">
                                        <button type="button" 
                                            wire:click="$set('fuel_type', 'gasolina')"
                                            class="rounded text-xs font-semibold flex items-center justify-center gap-1 transition-all
                                                {{ $fuel_type === 'gasolina' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                                            <span>⛽</span>
                                            <span class="truncate">Gasolina</span>
                                        </button>
                                        <button type="button" 
                                            wire:click="$set('fuel_type', 'diesel')"
                                            class="rounded text-xs font-semibold flex items-center justify-center gap-1 transition-all
                                                {{ $fuel_type === 'diesel' ? 'bg-amber-600 text-white shadow-sm' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                                            <span>🛢️</span>
                                            <span class="truncate">Diésel</span>
                                        </button>
                                        <button type="button" 
                                            wire:click="$set('fuel_type', 'electrico')"
                                            class="rounded text-xs font-semibold flex items-center justify-center gap-1 transition-all
                                                {{ $fuel_type === 'electrico' ? 'bg-emerald-600 text-white shadow-sm' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                                            <span>⚡</span>
                                            <span class="truncate">Eléctrico</span>
                                        </button>
                                    </div>
                                </div>

                                <div class="md:col-span-3">
                                    <label class="block font-medium text-xs text-gray-700 dark:text-gray-300">VIN / Número de Chasis</label>
                                    <input wire:model="vin" type="text" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm w-full uppercase placeholder:normal-case text-sm mt-1 h-10" placeholder="Número de identificación vehicular (opcional)">
                                </div>
                            </div>
                        </div>

                        <!-- SECCIÓN 3: ESTADO INICIAL -->
                        <div class="bg-gray-50/50 dark:bg-gray-800/80 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white pb-3 mb-4 border-b border-gray-200 dark:border-gray-700 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                3. Estado Inicial de Ingreso
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                                <div>
                                    <div class="h-5 flex items-center mb-1">
                                        <label class="font-medium text-xs text-gray-700 dark:text-gray-300">Kilometraje *</label>
                                    </div>
                                    <div class="relative">
                                        <input wire:model="mileage" type="number" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm w-full text-sm h-10 pr-10" placeholder="Ej. 75400">
                                        <span class="absolute inset-y-0 right-0 flex items-center pr-3 text-xs font-semibold text-gray-400 select-none">KM</span>
                                    </div>
                                    @error('mileage') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <div class="h-5 flex items-center justify-between mb-1">
                                        <label class="font-medium text-xs text-gray-700 dark:text-gray-300 whitespace-nowrap">
                                            @if($fuel_type === 'electrico')
                                                Nivel de Carga / Batería *
                                            @elseif($fuel_type === 'diesel')
                                                Nivel de Combustible * (Diésel)
                                            @else
                                                Nivel de Combustible * (Gasolina)
                                            @endif
                                        </label>
                                        @if($fuel_level)
                                            <span class="text-xs font-bold transition-colors
                                                {{ $fuel_level === 'E' ? 'text-red-600 dark:text-red-400' : '' }}
                                                {{ $fuel_level === '1/4' ? 'text-amber-600 dark:text-amber-400' : '' }}
                                                {{ $fuel_level === '1/2' ? 'text-blue-600 dark:text-blue-400' : '' }}
                                                {{ $fuel_level === '3/4' ? 'text-teal-600 dark:text-teal-400' : '' }}
                                                {{ $fuel_level === 'F' ? 'text-emerald-600 dark:text-emerald-400' : '' }}
                                            ">
                                                @if($fuel_type === 'electrico')
                                                    ⚡ {{ $fuel_level === 'E' ? '0% Batería (Descargado / Reserva)' : ($fuel_level === '1/4' ? '25% de Carga' : ($fuel_level === '1/2' ? '50% de Carga' : ($fuel_level === '3/4' ? '75% de Carga' : '100% Carga Completa'))) }}
                                                @elseif($fuel_type === 'diesel')
                                                    🛢️ {{ $fuel_level === 'E' ? 'Vacío / Reserva (E)' : ($fuel_level === '1/4' ? '1/4 de Tanque' : ($fuel_level === '1/2' ? 'Medio Tanque (1/2)' : ($fuel_level === '3/4' ? '3/4 de Tanque' : 'Tanque Lleno (F)'))) }}
                                                @else
                                                    ⛽ {{ $fuel_level === 'E' ? 'Vacío / Reserva (E)' : ($fuel_level === '1/4' ? '1/4 de Tanque' : ($fuel_level === '1/2' ? 'Medio Tanque (1/2)' : ($fuel_level === '3/4' ? '3/4 de Tanque' : 'Tanque Lleno (F)'))) }}
                                                @endif
                                            </span>
                                        @else
                                            <span class="text-[11px] text-gray-400 dark:text-gray-500 italic">Selecciona un nivel</span>
                                        @endif
                                    </div>
                                    
                                    <!-- Medidor gráfico en una sola línea compacta -->
                                    <div class="h-10 border border-gray-300 dark:border-gray-700 dark:bg-gray-900 bg-white rounded-lg p-1 shadow-sm flex items-stretch gap-1">
                                        <!-- Ícono bomba de combustible o enchufe eléctrico -->
                                        <div class="px-2.5 flex items-center justify-center text-gray-400 dark:text-gray-500 border-r border-gray-200 dark:border-gray-700/80">
                                            @if($fuel_type === 'electrico')
                                                <svg class="w-4 h-4 {{ $fuel_level === 'E' ? 'text-red-500' : ($fuel_level ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-400') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 {{ $fuel_level === 'E' ? 'text-red-500' : ($fuel_level ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-400') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a4 4 0 00-8 0v7a2 2 0 002 2h4a2 2 0 002-2v-7zm-4 7v-5m-6-1H7a2 2 0 01-2-2V7a2 2 0 012-2h2m4 0V3m0 2h4a2 2 0 012 2v2"></path>
                                                </svg>
                                            @endif
                                        </div>

                                        <!-- Botones de nivel adaptados -->
                                        <button type="button" 
                                            wire:click="$set('fuel_level', 'E')"
                                            class="flex-1 rounded text-xs font-bold transition-all duration-150 flex items-center justify-center
                                                {{ $fuel_level === 'E' ? 'bg-red-600 text-white shadow-sm ring-1 ring-red-400' : 'bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                                            {{ $fuel_type === 'electrico' ? '0%' : 'E' }}
                                        </button>

                                        <button type="button" 
                                            wire:click="$set('fuel_level', '1/4')"
                                            class="flex-1 rounded text-xs font-bold transition-all duration-150 flex items-center justify-center
                                                {{ $fuel_level === '1/4' ? 'bg-amber-500 text-white shadow-sm ring-1 ring-amber-400' : 'bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                                            {{ $fuel_type === 'electrico' ? '25%' : '1/4' }}
                                        </button>

                                        <button type="button" 
                                            wire:click="$set('fuel_level', '1/2')"
                                            class="flex-1 rounded text-xs font-bold transition-all duration-150 flex items-center justify-center
                                                {{ $fuel_level === '1/2' ? 'bg-blue-600 text-white shadow-sm ring-1 ring-blue-400' : 'bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                                            {{ $fuel_type === 'electrico' ? '50%' : '1/2' }}
                                        </button>

                                        <button type="button" 
                                            wire:click="$set('fuel_level', '3/4')"
                                            class="flex-1 rounded text-xs font-bold transition-all duration-150 flex items-center justify-center
                                                {{ $fuel_level === '3/4' ? 'bg-teal-600 text-white shadow-sm ring-1 ring-teal-400' : 'bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                                            {{ $fuel_type === 'electrico' ? '75%' : '3/4' }}
                                        </button>

                                        <button type="button" 
                                            wire:click="$set('fuel_level', 'F')"
                                            class="flex-1 rounded text-xs font-bold transition-all duration-150 flex items-center justify-center
                                                {{ $fuel_level === 'F' ? 'bg-emerald-600 text-white shadow-sm ring-1 ring-emerald-400' : 'bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                                            {{ $fuel_type === 'electrico' ? '100%' : 'F' }}
                                        </button>
                                    </div>
                                    @error('fuel_level') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                    </div>
                @endif

                <!-- STEP 2: CHECKLIST -->
                @if($currentStep === 2)
                    @php
                        $typeLabels = [
                            'pasola' => 'Pasola / Scooter',
                            'moto' => 'Motocicleta',
                            'hashback' => 'Hatchback',
                            'sedan' => 'Sedán',
                            'suv' => 'Camioneta (SUV)',
                            'pickup' => 'Pickup',
                            'pickup_double' => 'Pickup Doble Cabina',
                            'van' => 'Van / Furgoneta',
                            'camion' => 'Camión de Carga',
                            'cabezal' => 'Cabezal de Tráiler',
                            'bus_escolar' => 'Bus Escolar',
                            'bus' => 'Autobús',
                        ];
                        $currentTypeLabel = $typeLabels[$type] ?? 'Vehículo Estándar';
                    @endphp

                    <div class="space-y-6">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-gray-200 dark:border-gray-700 gap-2">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                    Listado de Recepción
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Marque el estado de cada componente inspeccionado.</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-100 dark:bg-indigo-900/60 text-indigo-800 dark:text-indigo-300">
                                    Inspección: {{ $currentTypeLabel }}
                                </span>
                            </div>
                        </div>

                        @if(empty($checklistItems))
                            <div class="bg-yellow-100 dark:bg-yellow-900/50 border border-yellow-200 dark:border-yellow-700 p-4 rounded-xl text-yellow-800 dark:text-yellow-200 text-sm">
                                No hay componentes configurados para esta categoría.
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($checklistItems as $section => $items)
                                    <div class="bg-gray-50/70 dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 p-4 rounded-xl shadow-sm">
                                        <h4 class="font-bold text-sm text-gray-800 dark:text-gray-200 mb-3 pb-2 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                                            <span>{{ $section }}</span>
                                            <span class="text-xs font-normal text-gray-400 dark:text-gray-500">({{ count($items) }} ítems)</span>
                                        </h4>
                                        <div class="space-y-3">
                                            @foreach($items as $item => $status)
                                                <div class="flex justify-between items-center text-sm py-0.5">
                                                    <span class="text-gray-700 dark:text-gray-300 font-medium text-xs sm:text-sm">{{ $item }}</span>
                                                    <div class="flex space-x-1.5 shrink-0">
                                                        <!-- Correcto -->
                                                        <button type="button" 
                                                            wire:click="$set('checklistItems.{{ $section }}.{{ $item }}', 'correct')"
                                                            class="w-7 h-7 rounded-full flex items-center justify-center border transition-all {{ $status === 'correct' ? 'bg-emerald-500 border-emerald-600 text-white shadow-sm ring-2 ring-emerald-300 dark:ring-emerald-800' : 'bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-400 hover:bg-emerald-50 dark:hover:bg-emerald-950/40' }}" 
                                                            title="Correcto / Buen Estado">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                                        </button>
                                                        
                                                        <!-- Desgaste -->
                                                        <button type="button" 
                                                            wire:click="$set('checklistItems.{{ $section }}.{{ $item }}', 'wear')"
                                                            class="w-7 h-7 rounded-full flex items-center justify-center border transition-all {{ $status === 'wear' ? 'bg-amber-500 border-amber-600 text-white shadow-sm ring-2 ring-amber-300 dark:ring-amber-800' : 'bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-400 hover:bg-amber-50 dark:hover:bg-amber-950/40' }}" 
                                                            title="Desgaste / Regular">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                        </button>

                                                        <!-- Falta / Malo -->
                                                        <button type="button" 
                                                            wire:click="$set('checklistItems.{{ $section }}.{{ $item }}', 'missing')"
                                                            class="w-7 h-7 rounded-full flex items-center justify-center border transition-all {{ $status === 'missing' ? 'bg-rose-500 border-rose-600 text-white shadow-sm ring-2 ring-rose-300 dark:ring-rose-800' : 'bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-400 hover:bg-rose-50 dark:hover:bg-rose-950/40' }}" 
                                                            title="Falta / Dañado">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
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
                    <div class="space-y-6 text-center py-5" 
                         wire:ignore
                         wire:key="canvas-{{ $this->vehicleImage }}"
                         x-data="{
                            isDrawing: false,
                            ctx: null,
                            canvas: null,
                            image: null,
                            imageSrc: '{{ $this->vehicleImage ? asset('img/vehicle-types/' . $this->vehicleImage) : '' }}',
                            showToast: false,
                            toastMessage: '',
                            toastType: 'success', // success, error
                            
                            init() {
                                if (!this.imageSrc) return;
                                
                                this.canvas = this.$refs.canvas;
                                this.ctx = this.canvas.getContext('2d');
                                
                                // Load Image
                                this.image = new Image();
                                // Avoid caching issues
                                this.image.src = this.imageSrc;
                                this.image.crossOrigin = 'Anonymous';
                                
                                this.image.onload = () => {
                                    // Resize canvas to match image aspect ratio but limit width
                                    const maxWidth = Math.min(600, window.innerWidth - 40);
                                    const scale = maxWidth / this.image.width;
                                    
                                    this.canvas.width = maxWidth;
                                    this.canvas.height = this.image.height * scale;
                                    
                                    // Draw background image
                                    this.ctx.drawImage(this.image, 0, 0, this.canvas.width, this.canvas.height);
                                    
                                    // Set drawing style explicitly after image load
                                    this.ctx.lineWidth = 4;
                                    this.ctx.lineCap = 'round';
                                    this.ctx.strokeStyle = '#FF0000'; // Red
                                };
                            },
                            
                            startDrawing(e) {
                                this.isDrawing = true;
                                this.draw(e);
                            },
                            
                            stopDrawing() {
                                if (this.isDrawing) {
                                    this.isDrawing = false;
                                    this.ctx.beginPath();
                                    // Do NOT sync automatically to avoid re-renders
                                }
                            },
                            
                            draw(e) {
                                if (!this.isDrawing) return;
                                e.preventDefault(); // Prevent scrolling on touch
                                
                                // Get coordinates
                                const rect = this.canvas.getBoundingClientRect();
                                let clientX, clientY;
                                
                                if (e.touches) {
                                  clientX = e.touches[0].clientX;
                                  clientY = e.touches[0].clientY;
                                } else {
                                  clientX = e.clientX;
                                  clientY = e.clientY;
                                }

                                const x = clientX - rect.left;
                                const y = clientY - rect.top;
                                
                                this.ctx.lineTo(x, y);
                                this.ctx.stroke();
                                this.ctx.beginPath();
                                this.ctx.moveTo(x, y);
                            },
                            
                            clearCanvas() {
                                this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
                                this.ctx.drawImage(this.image, 0, 0, this.canvas.width, this.canvas.height);
                                this.showNotification('Marcas borradas', 'info');
                            },
                            
                            saveCanvas() {
                                $wire.set('damageMap', this.canvas.toDataURL())
                                    .then(() => {
                                        this.showNotification('Diagrama guardado correctamente', 'success');
                                    })
                                    .catch(() => {
                                        this.showNotification('Error al guardar el diagrama', 'error');
                                    });
                            },

                            showNotification(message, type = 'success') {
                                this.toastMessage = message;
                                this.toastType = type;
                                this.showToast = true;
                                setTimeout(() => {
                                    this.showToast = false;
                                }, 3000);
                            }
                         }"
                         x-on:step-saved.window="saveCanvas()"
                    >
                        <!-- Toast Notification -->
                        <div x-show="showToast" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform translate-x-full"
                             x-transition:enter-end="opacity-100 transform translate-x-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform translate-x-0"
                             x-transition:leave-end="opacity-0 transform translate-x-full"
                             class="fixed top-4 right-4 z-50 flex items-center w-full max-w-xs p-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800"
                             style="display: none;"
                             role="alert">
                            <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 rounded-lg"
                                 :class="{
                                    'text-green-500 bg-green-100 dark:bg-green-800 dark:text-green-200': toastType === 'success',
                                    'text-red-500 bg-red-100 dark:bg-red-800 dark:text-red-200': toastType === 'error',
                                    'text-blue-500 bg-blue-100 dark:bg-blue-800 dark:text-blue-200': toastType === 'info'
                                 }">
                                <template x-if="toastType === 'success'">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                </template>
                                <template x-if="toastType === 'error'">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                </template>
                                <template x-if="toastType === 'info'">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                                </template>
                            </div>
                            <div class="ml-3 text-sm font-normal" x-text="toastMessage"></div>
                            <button type="button" @click="showToast = false" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700">
                                <span class="sr-only">Close</span>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            </button>
                        </div>
                        
                        @if($this->vehicleImage)
                            <div class="relative inline-block border border-gray-300 dark:border-gray-600 shadow-sm rounded">
                                <canvas x-ref="canvas"
                                        @mousedown="startDrawing"
                                        @mousemove="draw"
                                        @mouseup="stopDrawing"
                                        @mouseleave="stopDrawing"
                                        @touchstart.passive="startDrawing"
                                        @touchmove.passive="draw"
                                        @touchend="stopDrawing"
                                        class="cursor-crosshair bg-white touch-none"
                                ></canvas>
                            </div>
                            <div class="mt-2 flex justify-center space-x-2">
                                <button type="button" @click="clearCanvas" class="text-xs bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-800 px-3 py-1.5 rounded-lg hover:bg-red-200 dark:hover:bg-red-900/60 transition">
                                    Borrar Marcas
                                </button>
                                <!-- Explicit Save Button for Safety -->
                                <button type="button" @click="saveCanvas" class="text-xs bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300 border border-green-200 dark:border-green-800 px-3 py-1.5 rounded-lg hover:bg-green-200 dark:hover:bg-green-900/60 transition">
                                    Guardar Cambios
                                </button>
                            </div>
                        @else
                            <svg class="mx-auto h-24 w-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <p class="mt-1 text-sm text-gray-500">Seleccione un tipo de vehículo válido para ver el diagrama.</p>
                        @endif
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Diagrama de Daños</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Dibuje las marcas de daños directamente sobre la imagen. <strong>¡No olvide guardar!</strong></p>
                    </div>

                    <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Requerimiento / Solicitud del Cliente (Fallas Reportadas)</label>
                        <textarea wire:model="notes" rows="4" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm w-full text-sm" placeholder="Describa los problemas reportados por el cliente, requerimientos o servicios a realizar..."></textarea>
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Fotografías del Vehículo</label>
                        <div class="flex items-center justify-center w-full">
                            <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-300"><span class="font-semibold">Click para subir</span> o arrastrar y soltar</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG (MAX. 10MB)</p>
                                </div>
                                <input id="dropzone-file" type="file" wire:model.live="photos" multiple accept="image/*" capture="environment" class="hidden" />
                            </label>
                        </div>
                        @error('photos.*') <span class="text-red-500 text-xs block text-center mt-2">{{ $message }}</span> @enderror
                        @error('photos') <span class="text-red-500 text-xs block text-center mt-2">{{ $message }}</span> @enderror
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
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Resumen de Recepción</h3>
                            
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Vehículo</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $brand }} {{ $model }} {{ $year }} - {{ $plate }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Cliente</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                        <span class="font-bold">{{ $owner_name }}</span>
                                        @if($owner_phone) <span class="text-gray-500 dark:text-gray-400">({{ $owner_phone }})</span> @endif
                                        @if($owner_nit) <span class="text-xs bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 px-2 py-0.5 rounded ml-1">NIT: {{ $owner_nit }}</span> @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kilometraje</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ number_format($mileage) }} km</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Motorización</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white capitalize font-semibold flex items-center gap-1.5">
                                        @if($fuel_type === 'electrico')
                                            <span class="text-emerald-600 dark:text-emerald-400">⚡ Eléctrico (EV)</span>
                                        @elseif($fuel_type === 'diesel')
                                            <span class="text-amber-600 dark:text-amber-400">🛢️ Diésel</span>
                                        @else
                                            <span class="text-indigo-600 dark:text-indigo-400">⛽ Gasolina</span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                        {{ $fuel_type === 'electrico' ? 'Nivel de Batería' : 'Nivel de Combustible' }}
                                    </dt>
                                    <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                                        @if($fuel_type === 'electrico')
                                            {{ $fuel_level === 'E' ? '0% (Descargado / Reserva)' : ($fuel_level === '1/4' ? '25% de Carga' : ($fuel_level === '1/2' ? '50% de Carga' : ($fuel_level === '3/4' ? '75% de Carga' : '100% Carga Completa (F)'))) }}
                                        @elseif($fuel_type === 'diesel')
                                            {{ $fuel_level === 'E' ? 'Vacío / Reserva (E)' : ($fuel_level === '1/4' ? '1/4 de Tanque' : ($fuel_level === '1/2' ? 'Medio Tanque (1/2)' : ($fuel_level === '3/4' ? '3/4 de Tanque' : 'Tanque Lleno (F)'))) }}
                                        @else
                                            {{ $fuel_level === 'E' ? 'Vacío / Reserva (E)' : ($fuel_level === '1/4' ? '1/4 de Tanque' : ($fuel_level === '1/2' ? 'Medio Tanque (1/2)' : ($fuel_level === '3/4' ? '3/4 de Tanque' : 'Tanque Lleno (F)'))) }}
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Requerimiento del Cliente</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                        @if(!empty(trim($notes)))
                                            <p class="whitespace-pre-line text-sm text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-800/90 border border-gray-200 dark:border-gray-600 rounded-lg p-2.5 shadow-sm" title="{{ $notes }}">{{ $notes }}</p>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500 italic text-sm">Sin requerimientos o fallas registradas</span>
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                @endif

                <!-- Navigation Buttons -->
                <div class="flex items-center justify-between mt-8 border-t border-gray-200 dark:border-gray-700 pt-4">
                    
                    @if($currentStep > 1)
                        <button type="button" wire:click="previousStep" class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2 rounded shadow-sm hover:bg-gray-300 dark:hover:bg-gray-600">
                            Atrás
                        </button>
                    @else
                        <div></div>
                    @endif

                    @if($currentStep < $totalSteps)
                        <button type="button" 
                                wire:click="nextStep" 
                                @click="$dispatch('step-saved')"
                                class="bg-indigo-600 text-white px-4 py-2 rounded shadow-sm hover:bg-indigo-700">
                            Siguiente
                        </button>
                    @else
                        <button type="submit" 
                                wire:loading.attr="disabled"
                                wire:target="submit"
                                @click="$dispatch('step-saved')"
                                class="bg-indigo-600 text-white px-4 py-2 rounded shadow-sm hover:bg-indigo-700 font-bold flex items-center">
                            <svg wire:loading wire:target="submit" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="submit">Crear Orden de Recepción</span>
                            <span wire:loading wire:target="submit">Procesando...</span>
                        </button>
                    @endif
                </div>

            </form>
        </div>
    </div>
</div>
