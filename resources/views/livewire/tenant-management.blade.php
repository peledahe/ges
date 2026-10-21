<div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div>
                <div class="flex items-center gap-3">
                    <div class="p-2.5 bg-indigo-50 dark:bg-indigo-900/40 rounded-xl text-indigo-600 dark:text-indigo-400">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">Administración de Talleres (Tenants)</h1>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 dark:bg-amber-900/60 dark:text-amber-300">
                                Root Superadmin
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Supervisa los talleres del sistema, asigna los tipos de vehículos que atienden y configura las áreas de trabajo de cada taller.
                        </p>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button wire:click="createTenant"
                        type="button"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-md shadow-indigo-600/20 hover:shadow-indigo-600/30 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nuevo Taller
                </button>
            </div>
        </div>

        <!-- Dashboard KPI Metrics -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Total Talleres -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm relative overflow-hidden">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total Talleres</span>
                    <span class="p-2 bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </span>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ $metrics['total_tenants'] }}</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">talleres</span>
                </div>
                <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400 gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>{{ $metrics['active_tenants'] }} activos</span>
                    <span class="text-gray-300 dark:text-gray-600">•</span>
                    <span>{{ $metrics['inactive_tenants'] }} inactivos</span>
                </div>
            </div>

            <!-- Talleres Activos -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm relative overflow-hidden">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Estado Activo</span>
                    <span class="p-2 bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-emerald-600 dark:text-emerald-400">{{ $metrics['active_tenants'] }}</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">operando</span>
                </div>
                <div class="mt-2 text-xs text-emerald-600 dark:text-emerald-400 font-medium">
                    {{ $metrics['total_tenants'] > 0 ? round(($metrics['active_tenants'] / $metrics['total_tenants']) * 100) : 0 }}% de disponibilidad
                </div>
            </div>

            <!-- Total Órdenes Global -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm relative overflow-hidden">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Órdenes de Trabajo</span>
                    <span class="p-2 bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </span>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ $metrics['total_orders'] }}</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">en plataforma</span>
                </div>
                <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                    Historial global acumulado
                </div>
            </div>

            <!-- Total Vehículos -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm relative overflow-hidden">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Vehículos Registrados</span>
                    <span class="p-2 bg-amber-50 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </span>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ $metrics['total_vehicles'] }}</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">unidades</span>
                </div>
                <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                    Flota total atendida
                </div>
            </div>

            <!-- Total Usuarios -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm relative overflow-hidden">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Usuarios Totales</span>
                    <span class="p-2 bg-purple-50 dark:bg-purple-900/40 text-purple-600 dark:text-purple-400 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </span>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ $metrics['total_users'] }}</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">cuentas</span>
                </div>
                <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                    Admins, mecánicos y clientes
                </div>
            </div>
        </div>

        <!-- Controls & Filters -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col sm:flex-row gap-3 items-center justify-between">
            <div class="relative w-full sm:w-96">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search"
                       type="text"
                       placeholder="Buscar por taller, slug, admin o email..."
                       class="w-full pl-10 pr-4 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
            </div>

            <div class="flex items-center gap-3 w-full sm:w-auto">
                <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 whitespace-nowrap">Filtrar Estado:</label>
                <select wire:model.live="statusFilter"
                        class="text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-gray-900 dark:text-gray-100 px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="all">Todos los Talleres</option>
                    <option value="active">Solo Activos</option>
                    <option value="inactive">Solo Inactivos</option>
                </select>
            </div>
        </div>

        <!-- Tenants List Table -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-900/60 border-b border-gray-200 dark:border-gray-700 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            <th class="py-4 px-6">Taller</th>
                            <th class="py-4 px-6">Administrador / Contacto</th>
                            <th class="py-4 px-6">Vehículos que Atiende</th>
                            <th class="py-4 px-6">Áreas del Taller</th>
                            <th class="py-4 px-6 text-center">Estado</th>
                            <th class="py-4 px-6 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        @forelse ($tenants as $tenant)
                            <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-700/40 transition">
                                <!-- Taller -->
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white font-black flex items-center justify-center text-base shadow-sm">
                                            {{ strtoupper(substr($tenant->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                                {{ $tenant->name }}
                                                <span class="text-xs font-normal text-gray-400 font-mono">#{{ $tenant->id }}</span>
                                            </div>
                                            <div class="text-xs text-indigo-600 dark:text-indigo-400 font-mono mt-0.5">
                                                slug: {{ $tenant->slug }}
                                            </div>
                                            @if ($tenant->address)
                                                <div class="text-xs text-gray-500 dark:text-gray-400 line-clamp-1 mt-0.5" title="{{ $tenant->address }}">
                                                    📍 {{ $tenant->address }}
                                                </div>
                                            @endif
                                            <div class="mt-1 flex flex-wrap items-center gap-1.5">
                                                <a href="{{ route('rastreo.tenant', $tenant->slug) }}" target="_blank" class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/50 dark:hover:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800 transition" title="Abrir portal de consulta pública por placa de este taller">
                                                    <span>🔍 /rastreo/{{ $tenant->slug }} ↗</span>
                                                </a>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                                                    💱 1 US$ = Q{{ number_format((float) ($tenant->exchange_rate ?? 7.80), 2) }}
                                                </span>
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-300">
                                                    Base: {{ $tenant->default_currency ?? 'GTQ' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Admin / Contacto -->
                                <td class="py-4 px-6">
                                    <div class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $tenant->admin_name }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 flex flex-col gap-0.5">
                                        <a href="mailto:{{ $tenant->email }}" class="hover:underline flex items-center gap-1">
                                            ✉️ {{ $tenant->email }}
                                        </a>
                                        @if ($tenant->phone)
                                            <span class="flex items-center gap-1">
                                                📞 {{ $tenant->phone }}
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Vehículos que Atiende -->
                                <td class="py-4 px-6">
                                    @php
                                        $allowed = $tenant->allowed_vehicle_types;
                                        $totalAllowed = is_array($allowed) && !empty($allowed) ? count($allowed) : count($allVehicleTypes);
                                        $isAll = empty($allowed) || count($allowed) === count($allVehicleTypes);
                                    @endphp
                                    <div class="flex flex-col gap-1.5 max-w-xs">
                                        <div class="flex items-center gap-2">
                                            @if ($isAll)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300">
                                                    ✓ Todos ({{ count($allVehicleTypes) }} tipos)
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300">
                                                    {{ $totalAllowed }} tipos asignados
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex flex-wrap gap-1">
                                            @if (!$isAll && is_array($allowed))
                                                @foreach (array_slice($allowed, 0, 4) as $typeKey)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                                        {{ $allVehicleTypes[$typeKey] ?? $typeKey }}
                                                    </span>
                                                @endforeach
                                                @if (count($allowed) > 4)
                                                    <span class="text-[11px] text-gray-400 font-semibold self-center">
                                                        +{{ count($allowed) - 4 }} más
                                                    </span>
                                                @endif
                                            @else
                                                <span class="text-xs text-gray-500 dark:text-gray-400">Atiende motos, livianos y pesados</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Áreas del Taller -->
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800">
                                            {{ $tenant->areas_count }} {{ $tenant->areas_count === 1 ? 'área' : 'áreas' }}
                                        </span>
                                        <button wire:click="manageAreas({{ $tenant->id }})"
                                                type="button"
                                                title="Gestionar Áreas de este Taller"
                                                class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 hover:underline flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            Configurar
                                        </button>
                                    </div>
                                    <div class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $tenant->work_orders_count }} órdenes • {{ $tenant->vehicles_count }} vehículos
                                    </div>
                                </td>

                                <!-- Estado Toggle -->
                                <td class="py-4 px-6 text-center">
                                    <button wire:click="toggleTenantStatus({{ $tenant->id }})"
                                            type="button"
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition {{ $tenant->is_active ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300 hover:bg-emerald-200' : 'bg-rose-100 text-rose-800 dark:bg-rose-900/50 dark:text-rose-300 hover:bg-rose-200' }}">
                                        <span class="w-2 h-2 rounded-full {{ $tenant->is_active ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                        {{ $tenant->is_active ? 'Activo' : 'Inactivo' }}
                                    </button>
                                </td>

                                <!-- Acciones -->
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button wire:click="editTenant({{ $tenant->id }})"
                                                type="button"
                                                title="Editar Taller y Tipos de Vehículos"
                                                class="p-2 text-indigo-600 hover:bg-indigo-50 dark:text-indigo-400 dark:hover:bg-indigo-900/40 rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button wire:click="manageAreas({{ $tenant->id }})"
                                                type="button"
                                                title="Gestionar Áreas de Trabajo"
                                                class="p-2 text-purple-600 hover:bg-purple-50 dark:text-purple-400 dark:hover:bg-purple-900/40 rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                            </svg>
                                        </button>
                                        <button wire:click="confirmTenantDeletion({{ $tenant->id }})"
                                                type="button"
                                                title="Eliminar Taller"
                                                class="p-2 text-rose-600 hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-900/40 rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center gap-3">
                                        <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-400">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                        </div>
                                        <p class="font-medium">No se encontraron talleres registrados con ese criterio.</p>
                                        <button wire:click="createTenant" class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">
                                            Crear primer taller ahora
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($tenants->hasPages())
                <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $tenants->links() }}
                </div>
            @endif
        </div>

    </div>

    <!-- Modal de Creación / Edición de Tenant -->
    <x-dialog-modal wire:model.live="managingTenant" maxWidth="5xl">
        <x-slot name="title">
            <div class="flex items-center gap-2 text-lg font-bold text-gray-900 dark:text-white">
                <div class="p-2 bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <span>{{ $tenantId ? 'Editar Taller (Tenant) #' . $tenantId : 'Registrar Nuevo Taller (Tenant)' }}</span>
            </div>
        </x-slot>

        <x-slot name="content">
            <div class="space-y-6 max-h-[75vh] overflow-y-auto -mr-6 pr-6 pl-0.5 custom-scrollbar">

                <!-- Datos Generales -->
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-3">Información General</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nombre del Taller -->
                        <div>
                            <x-label for="name" value="Nombre del Taller *" class="text-xs font-semibold" />
                            <x-input id="name" type="text" class="mt-1 block w-full text-sm" wire:model.live="name" placeholder="Ej: Taller Mecánico Los Andes" />
                            <x-input-error for="name" class="mt-1 text-xs" />
                        </div>

                        <!-- Slug -->
                        <div>
                            <x-label for="slug" value="Slug Identificador (URL) *" class="text-xs font-semibold" />
                            <x-input id="slug" type="text" class="mt-1 block w-full text-sm font-mono" wire:model="slug" placeholder="los-andes" />
                            <x-input-error for="slug" class="mt-1 text-xs" />
                        </div>

                        <!-- Administrador Responsable -->
                        <div>
                            <x-label for="admin_name" value="Nombre del Administrador *" class="text-xs font-semibold" />
                            <x-input id="admin_name" type="text" class="mt-1 block w-full text-sm" wire:model="admin_name" placeholder="Ej: Roberto Gómez" />
                            <x-input-error for="admin_name" class="mt-1 text-xs" />
                        </div>

                        <!-- Email de Contacto -->
                        <div>
                            <x-label for="email" value="Correo Electrónico de Contacto *" class="text-xs font-semibold" />
                            <x-input id="email" type="email" class="mt-1 block w-full text-sm" wire:model="email" placeholder="admin@tallerlosandes.com" />
                            <x-input-error for="email" class="mt-1 text-xs" />
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <x-label for="phone" value="Teléfono de Contacto" class="text-xs font-semibold" />
                            <x-input id="phone" type="text" class="mt-1 block w-full text-sm" wire:model="phone" placeholder="+502 1234 5678" />
                            <x-input-error for="phone" class="mt-1 text-xs" />
                        </div>

                        <!-- Límite de Usuarios -->
                        <div>
                            <x-label for="users_limit" value="Límite de Usuarios Permitidos" class="text-xs font-semibold" />
                            <x-input id="users_limit" type="number" min="1" max="500" class="mt-1 block w-full text-sm" wire:model="users_limit" />
                            <x-input-error for="users_limit" class="mt-1 text-xs" />
                        </div>

                        <!-- Tasa de Cambio Oficial (1 US$ = X Quetzales) -->
                        <div>
                            <x-label for="tenant_exchange_rate" value="Tasa de Cambio (1 US$ = ? Q) *" class="text-xs font-semibold" />
                            <x-input id="tenant_exchange_rate" type="number" step="0.0001" min="0.01" max="999.99" class="mt-1 block w-full text-sm font-bold text-indigo-600 dark:text-indigo-400" wire:model="exchange_rate" placeholder="7.8000" />
                            <x-input-error for="exchange_rate" class="mt-1 text-xs" />
                        </div>

                        <!-- Moneda Predeterminada -->
                        <div>
                            <x-label for="tenant_default_currency" value="Moneda Predeterminada *" class="text-xs font-semibold" />
                            <select id="tenant_default_currency" wire:model="default_currency" class="mt-1 block w-full text-sm rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-medium">
                                <option value="GTQ">Quetzales (Q / GTQ)</option>
                                <option value="USD">Dólares (US$ / USD)</option>
                            </select>
                            <x-input-error for="default_currency" class="mt-1 text-xs" />
                        </div>

                        <!-- Dirección Física -->
                        <div class="md:col-span-2">
                            <x-label for="address" value="Dirección Física del Taller" class="text-xs font-semibold" />
                            <textarea id="address" wire:model="address" rows="2" class="mt-1 block w-full text-sm rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Zona, Calle, Ciudad o Referencias"></textarea>
                            <x-input-error for="address" class="mt-1 text-xs" />
                        </div>

                        <!-- Estado Activo -->
                        <div class="md:col-span-2 flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="is_active" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                            <div>
                                <span class="text-sm font-bold text-gray-900 dark:text-white">Taller Activo en la Plataforma</span>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Si se desactiva, los usuarios de este taller no podrán iniciar sesión ni registrar nuevas órdenes.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-200 dark:border-gray-700">

                <!-- ASIGNACIÓN DE TIPOS DE VEHÍCULOS -->
                <div>
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-3">
                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">
                                🚗 Tipos de Vehículos que Atiende el Taller
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                Selecciona cuáles vehículos estarán disponibles en el formulario de recepción para este taller.
                            </p>
                        </div>

                        <!-- Presets de Selección Rápida -->
                        <div class="flex flex-wrap gap-1.5">
                            <button type="button" wire:click="selectAllVehicleTypes" class="px-2 py-1 text-xs font-semibold rounded bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 transition">
                                Todos
                            </button>
                            <button type="button" wire:click="selectMotorcyclesOnly" class="px-2 py-1 text-xs font-semibold rounded bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 transition">
                                Solo Motos
                            </button>
                            <button type="button" wire:click="selectLightVehiclesOnly" class="px-2 py-1 text-xs font-semibold rounded bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 transition">
                                Livianos
                            </button>
                            <button type="button" wire:click="selectHeavyVehiclesOnly" class="px-2 py-1 text-xs font-semibold rounded bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 transition">
                                Pesados
                            </button>
                            <button type="button" wire:click="clearVehicleTypes" class="px-2 py-1 text-xs font-semibold rounded bg-rose-50 hover:bg-rose-100 dark:bg-rose-900/40 dark:hover:bg-rose-900/60 text-rose-600 dark:text-rose-400 transition">
                                Limpiar
                            </button>
                        </div>
                    </div>

                    <!-- Grid de Selección de Tipos de Vehículo -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                        @foreach ($allVehicleTypes as $vKey => $vLabel)
                            @php
                                $isChecked = in_array($vKey, $allowed_vehicle_types);
                            @endphp
                            <label class="relative flex items-center gap-3 p-3 rounded-xl border cursor-pointer select-none transition-all {{ $isChecked ? 'bg-indigo-50/70 border-indigo-400 dark:bg-indigo-950/40 dark:border-indigo-600 shadow-sm' : 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                                <input type="checkbox"
                                       wire:model="allowed_vehicle_types"
                                       value="{{ $vKey }}"
                                       class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:ring-indigo-500 w-4 h-4">
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs font-bold text-gray-900 dark:text-white truncate">
                                        {{ $vLabel }}
                                    </div>
                                    <div class="text-[10px] text-gray-400 font-mono mt-0.5">
                                        {{ $vKey }}
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    <div class="mt-2 text-xs text-gray-500 dark:text-gray-400 italic">
                        Tipos seleccionados: <span class="font-bold text-indigo-600 dark:text-indigo-400">{{ count($allowed_vehicle_types) }}</span> de {{ count($allVehicleTypes) }}.
                    </div>
                </div>

            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('managingTenant', false)" wire:loading.attr="disabled">
                Cancelar
            </x-secondary-button>

            <x-button class="ms-3 bg-indigo-600 hover:bg-indigo-700" wire:click="saveTenant" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="saveTenant">
                    {{ $tenantId ? 'Actualizar Taller' : 'Crear Taller' }}
                </span>
                <span wire:loading wire:target="saveTenant" class="inline-flex items-center gap-1.5">
                    <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    Guardando...
                </span>
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Modal de Gestión de Áreas del Taller -->
    <x-dialog-modal wire:model.live="managingAreas" maxWidth="4xl">
        <x-slot name="title">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex items-center gap-2 text-lg font-bold text-gray-900 dark:text-white">
                    <div class="p-2 bg-purple-50 dark:bg-purple-900/40 text-purple-600 dark:text-purple-400 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div>
                        <span>Áreas de Trabajo del Taller</span>
                        @if ($currentAreasTenant)
                            <span class="block text-xs font-normal text-gray-500 dark:text-gray-400">
                                Taller: <strong class="text-indigo-600 dark:text-indigo-400">{{ $currentAreasTenant->name }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <button wire:click="loadRecommendedAreas"
                        type="button"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-lg bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 hover:bg-indigo-100 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Cargar Áreas Recomendadas
                </button>
            </div>
        </x-slot>

        <x-slot name="content">
            <div class="space-y-6 max-h-[75vh] overflow-y-auto -mr-6 pr-6 pl-0.5 custom-scrollbar">

                <!-- Alerta de Área Asignada para Generar Cotización -->
                @php
                    $quotationArea = collect($currentTenantAreas)->firstWhere('can_generate_quotation', true);
                @endphp
                @if (!$quotationArea)
                    <div class="p-3 bg-amber-50 dark:bg-amber-950/40 border border-amber-300 dark:border-amber-700/60 rounded-xl flex items-start gap-3">
                        <span class="text-xl">⚠️</span>
                        <div class="text-xs text-amber-900 dark:text-amber-200">
                            <strong class="font-bold">Ninguna área tiene asignada la opción de generar cotizaciones.</strong>
                            <p class="mt-0.5 text-amber-700 dark:text-amber-300">
                                Haz clic en el botón <strong class="underline">"📝 Cotiza"</strong> en el listado de abajo para habilitar qué área (por ejemplo, <em>Diagnóstico y Presupuestos</em>) tendrá la opción de generar y editar cotizaciones.
                            </p>
                        </div>
                    </div>
                @else
                    <div class="p-3 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 rounded-xl flex items-center justify-between text-xs text-emerald-900 dark:text-emerald-200">
                        <div class="flex items-center gap-2">
                            <span class="text-base">📝</span>
                            <span>Área autorizada para generar cotizaciones del taller: <strong class="font-bold underline text-emerald-700 dark:text-emerald-300">{{ $quotationArea->name }}</strong></span>
                        </div>
                        <span class="text-[10px] font-black uppercase bg-emerald-200 dark:bg-emerald-900/60 text-emerald-900 dark:text-emerald-200 px-2 py-0.5 rounded-full">
                            Habilitada
                        </span>
                    </div>
                @endif

                <!-- Formulario Agregar Nueva Área -->
                <div class="p-4 bg-gray-50 dark:bg-gray-900/70 rounded-xl border border-gray-200 dark:border-gray-700">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-3">
                        ➕ Agregar Nueva Área
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <x-label for="newAreaName" value="Nombre del Área *" class="text-xs font-semibold" />
                            <x-input id="newAreaName" type="text" class="mt-1 block w-full text-sm" wire:model.live="newAreaName" placeholder="Ej: Diagnóstico y Presupuestos" />
                            <x-input-error for="newAreaName" class="mt-1 text-xs" />
                        </div>
                        <div>
                            <x-label for="newAreaDescription" value="Descripción / Actividades" class="text-xs font-semibold" />
                            <x-input id="newAreaDescription" type="text" class="mt-1 block w-full text-sm" wire:model="newAreaDescription" placeholder="Ej: Escaneo y cotización de repuestos" />
                            <x-input-error for="newAreaDescription" class="mt-1 text-xs" />
                        </div>
                    </div>

                    <!-- Permisos y Roles del Área -->
                    <div class="mt-3 grid grid-cols-1 sm:grid-cols-3 gap-2.5 pt-3 border-t border-gray-200 dark:border-gray-700/60 text-xs">
                        <!-- Permiso Cotizar -->
                        <label class="flex items-center gap-2 p-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <input type="checkbox" wire:model="newAreaCanGenerateQuotation" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-4 h-4">
                            <div>
                                <span class="font-bold text-gray-900 dark:text-white block">📝 Generar Cotización</span>
                                <span class="text-[10px] text-gray-400">Permite crear presupuesto</span>
                            </div>
                        </label>

                        <!-- Permiso Ver Montos -->
                        <label class="flex items-center gap-2 p-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <input type="checkbox" wire:model="newAreaCanViewQuotationAmount" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-4 h-4">
                            <div>
                                <span class="font-bold text-gray-900 dark:text-white block">💰 Ver Montos</span>
                                <span class="text-[10px] text-gray-400">Mostrar precios a operarios</span>
                            </div>
                        </label>

                        <!-- Permiso Cobrar -->
                        <label class="flex items-center gap-2 p-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <input type="checkbox" wire:model="newAreaCanCollectPayment" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-4 h-4">
                            <div>
                                <span class="font-bold text-gray-900 dark:text-white block">💳 Permitir Cobro</span>
                                <span class="text-[10px] text-gray-400">Registrar pagos y liquidar</span>
                            </div>
                        </label>
                    </div>

                    <div class="mt-3 flex justify-end">
                        <button type="button" wire:click="addArea" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow transition">
                            Añadir Área
                        </button>
                    </div>
                </div>

                <!-- Listado de Áreas Actuales -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">
                        Áreas Configuradas ({{ count($currentTenantAreas) }})
                    </h4>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700 max-h-80 overflow-y-auto rounded-xl border border-gray-200 dark:border-gray-700 custom-scrollbar">
                        @forelse ($currentTenantAreas as $area)
                            <div class="p-3 bg-white dark:bg-gray-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:bg-gray-50 dark:hover:bg-gray-700/40 transition">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-xs mt-0.5">
                                        {{ $loop->iteration }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-sm text-gray-900 dark:text-white flex flex-wrap items-center gap-1.5">
                                            {{ $area->name }}
                                            @if ($area->is_delivery)
                                                <span class="text-[10px] font-black bg-emerald-100 text-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-300 px-2 py-0.5 rounded-full border border-emerald-300 dark:border-emerald-700">
                                                    🔑 Entrega
                                                </span>
                                            @endif
                                            @if ($area->default_status)
                                                <span class="text-[10px] font-bold bg-indigo-50 text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-300 px-2 py-0.5 rounded-full border border-indigo-200 dark:border-indigo-800">
                                                    Estado: {{ ucfirst(str_replace('_', ' ', $area->default_status)) }}
                                                </span>
                                            @endif
                                            @if ($area->work_orders_count > 0)
                                                <span class="text-[11px] font-semibold text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/40 px-2 py-0.5 rounded-full">
                                                    {{ $area->work_orders_count }} órdenes
                                                </span>
                                            @endif
                                        </div>

                                        @if ($area->description)
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                {{ $area->description }}
                                            </p>
                                        @endif

                                        <!-- Badges Interactivos de Configuración de Permisos -->
                                        <div class="flex flex-wrap items-center gap-1.5 mt-2">
                                            <!-- Permiso Generar Cotización -->
                                            <button wire:click="toggleAreaQuotation({{ $area->id }})"
                                                    type="button"
                                                    title="Clic para alternar si esta área puede generar cotizaciones"
                                                    class="inline-flex items-center gap-1 text-[10px] font-bold px-2.5 py-0.5 rounded-full border transition {{ $area->can_generate_quotation ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-950/60 dark:text-indigo-300 border-indigo-300 dark:border-indigo-700' : 'bg-gray-100 text-gray-500 dark:bg-gray-700/60 dark:text-gray-400 border-gray-200 dark:border-gray-600 hover:bg-gray-200' }}">
                                                📝 Cotiza: {{ $area->can_generate_quotation ? 'Sí' : 'No' }}
                                            </button>

                                            <!-- Permiso Ver Montos -->
                                            <button wire:click="toggleAreaViewAmount({{ $area->id }})"
                                                    type="button"
                                                    title="Clic para alternar si los operarios de esta área pueden ver montos económicos"
                                                    class="inline-flex items-center gap-1 text-[10px] font-bold px-2.5 py-0.5 rounded-full border transition {{ $area->can_view_quotation_amount ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 border-emerald-300 dark:border-emerald-700' : 'bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300 border-rose-300 dark:border-rose-700' }}">
                                                💰 Monto: {{ $area->can_view_quotation_amount ? 'Visible' : 'Oculto' }}
                                            </button>

                                            <!-- Permiso Cobrar -->
                                            <button wire:click="toggleAreaCollectPayment({{ $area->id }})"
                                                    type="button"
                                                    title="Clic para alternar si en esta área se pueden cobrar y registrar pagos"
                                                    class="inline-flex items-center gap-1 text-[10px] font-bold px-2.5 py-0.5 rounded-full border transition {{ $area->can_collect_payment ? 'bg-purple-100 text-purple-800 dark:bg-purple-950/60 dark:text-purple-300 border-purple-300 dark:border-purple-700' : 'bg-gray-100 text-gray-500 dark:bg-gray-700/60 dark:text-gray-400 border-gray-200 dark:border-gray-600 hover:bg-gray-200' }}">
                                                💳 Cobro: {{ $area->can_collect_payment ? 'Habilitado' : 'No' }}
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-end gap-1">
                                    <button type="button"
                                            wire:click="confirmAreaDeletion({{ $area->id }})"
                                            class="p-1.5 text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/40 rounded-lg transition"
                                            title="Eliminar área">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-center text-xs text-gray-500 dark:text-gray-400">
                                Este taller no tiene áreas asignadas. Puedes agregar una manualmente o usar el botón "Cargar Áreas Recomendadas".
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('managingAreas', false)">
                Cerrar
            </x-secondary-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Modal de Confirmación para Eliminar Tenant -->
    <x-confirmation-modal wire:model.live="confirmingTenantDeletion">
        <x-slot name="title">
            ¿Eliminar el taller "{{ $tenantToDeleteName }}"?
        </x-slot>

        <x-slot name="content">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Esta acción es irreversible. Se eliminarán los datos relacionados a este taller, incluyendo sus áreas de trabajo, vehículos y órdenes registradas bajo su identificador.
            </p>
            <p class="mt-2 text-xs font-semibold text-rose-600 dark:text-rose-400">
                ¿Estás seguro de que deseas continuar con la eliminación?
            </p>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmingTenantDeletion', false)">
                Cancelar
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="deleteTenant" wire:loading.attr="disabled">
                Sí, Eliminar Taller
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>

    <!-- Modal de Confirmación para Eliminar Área -->
    <x-confirmation-modal wire:model.live="confirmingAreaDeletion">
        <x-slot name="title">
            ¿Eliminar el área "{{ $areaToDeleteName }}"?
        </x-slot>

        <x-slot name="content">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                ¿Estás seguro de eliminar esta área del taller? Si existen órdenes de trabajo activas en esta área, su seguimiento podría verse afectado.
            </p>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmingAreaDeletion', false)">
                Cancelar
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="deleteArea" wire:loading.attr="disabled">
                Sí, Eliminar Área
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>

</div>
