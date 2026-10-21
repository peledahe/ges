<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        @if($this->isDemoTenant)
            <!-- Demo Tenant Read-Only Notice -->
            <div class="mb-6 p-4 bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/60 rounded-2xl flex items-start sm:items-center justify-between gap-4 shadow-sm">
                <div class="flex items-start sm:items-center gap-3">
                    <div class="p-2 bg-amber-500/10 dark:bg-amber-500/20 rounded-xl text-amber-600 dark:text-amber-400 flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-amber-900 dark:text-amber-200">
                            Entorno de Demostración — Modo Solo Lectura
                        </h3>
                        <p class="text-xs text-amber-700 dark:text-amber-300/90 mt-0.5">
                            Puedes visualizar la lista de usuarios, sus roles y accesos a áreas. La creación, modificación y eliminación de usuarios están deshabilitadas para proteger la integridad del entorno de prueba.
                        </p>
                    </div>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-200/80 dark:bg-amber-900/60 text-amber-900 dark:text-amber-200 flex-shrink-0">
                    Solo Lectura
                </span>
            </div>
        @endif

        <!-- Header & Toolbar -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h2 class="font-black text-2xl text-gray-800 dark:text-gray-100 leading-tight">
                    {{ __('Gestión de Usuarios y Áreas') }}
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Asigna qué áreas del taller puede ver y gestionar cada usuario en el tablero Kanban.
                </p>
            </div>
            <div class="flex items-center space-x-2 w-full sm:w-auto">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar usuarios..." class="rounded-xl text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 w-full sm:w-64">
                @if($this->isDemoTenant)
                    <button type="button" disabled class="bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 font-bold py-2 px-4 rounded-xl text-sm shadow cursor-not-allowed opacity-80 inline-flex items-center gap-1.5" title="No disponible en el taller de demostración">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <span>Nuevo Usuario</span>
                    </button>
                @else
                    <button wire:click="create" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-xl text-sm shadow transition">
                        Nuevo Usuario
                    </button>
                @endif
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl border border-gray-200 dark:border-gray-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Usuario</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Rol</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Áreas con Acceso (Kanban)</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Datos de Contacto</th>
                        <th class="px-6 py-3.5 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-700/40 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 inline-flex text-xs font-bold rounded-full 
                                    {{ $user->role === 'admin' ? 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300' : '' }}
                                    {{ $user->role === 'manager' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300' : '' }}
                                    {{ $user->role === 'recepcion' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300' : '' }}
                                    {{ $user->role === 'presupuestos' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300' : '' }}
                                    {{ $user->role === 'client' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' : '' }}
                                ">
                                    {{ $roles[$user->role] ?? ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->isActive())
                                    <span class="px-2.5 py-1 inline-flex text-xs font-bold rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                                        Activo
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 inline-flex text-xs font-bold rounded-full bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300">
                                        Inactivo
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($user->isAdmin())
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300">
                                        ★ Todas las Áreas
                                    </span>
                                @elseif($user->areas->isNotEmpty())
                                    <div class="flex flex-wrap gap-1 max-w-xs">
                                        @foreach($user->areas as $uArea)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">
                                                {{ $uArea->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @elseif($user->area)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                        {{ $user->area->name }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400 italic">Acceso Total / Sin restricción</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs">
                                @if($user->can_view_contact_info)
                                    <span class="text-emerald-600 dark:text-emerald-400 font-bold">✓ Permitido</span>
                                @else
                                    <span class="text-gray-400">✗ Restringido</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium">
                                @if($this->isDemoTenant)
                                    <button wire:click="edit({{ $user->id }})" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 hover:bg-indigo-50 dark:hover:bg-indigo-950/30 font-bold transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Ver Detalle
                                    </button>
                                @else
                                    <button wire:click="edit({{ $user->id }})" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 font-bold mr-3">Editar</button>
                                    <button wire:click="confirmDeletion({{ $user->id }})" class="text-rose-600 hover:text-rose-900 dark:text-rose-400 dark:hover:text-rose-300 font-bold">Eliminar</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-400 italic">No se encontraron usuarios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $users->links() }}
            </div>
        </div>

        <!-- Create/Edit/View Modal -->
        <x-dialog-modal wire:model="managingUserObject" maxWidth="2xl">
            <x-slot name="title">
                <span class="font-black text-lg">
                    @if($this->isDemoTenant)
                        {{ __('Detalle de Usuario (Solo Lectura)') }}
                    @else
                        {{ $userId ? __('Editar Usuario') : __('Nuevo Usuario') }}
                    @endif
                </span>
            </x-slot>

            <x-slot name="content">
                <div class="space-y-4 max-h-[70vh] overflow-y-auto pr-1">
                    @if($this->isDemoTenant)
                        <div class="p-3 bg-amber-50 dark:bg-amber-950/40 rounded-xl border border-amber-200 dark:border-amber-800 text-xs text-amber-800 dark:text-amber-200 flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <span>Modo de visualización: No es posible alterar la configuración de este usuario en la empresa demo.</span>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Name -->
                        <div class="sm:col-span-2">
                            <x-label for="name" value="{{ __('Nombre Completo *') }}" class="text-xs font-semibold" />
                            <x-input id="name" type="text" class="mt-1 block w-full text-sm disabled:opacity-75 disabled:bg-gray-100 dark:disabled:bg-gray-800" wire:model="name" placeholder="Ej: Juan Pérez" :disabled="$this->isDemoTenant" />
                            <x-input-error for="name" class="mt-1 text-xs" />
                        </div>

                        <!-- Email -->
                        <div>
                            <x-label for="email" value="{{ __('Correo Electrónico *') }}" class="text-xs font-semibold" />
                            <x-input id="email" type="email" class="mt-1 block w-full text-sm disabled:opacity-75 disabled:bg-gray-100 dark:disabled:bg-gray-800" wire:model="email" placeholder="usuario@taller.com" :disabled="$this->isDemoTenant" />
                            <x-input-error for="email" class="mt-1 text-xs" />
                        </div>

                        <!-- Role -->
                        <div>
                            <x-label for="role" value="{{ __('Rol en el Taller *') }}" class="text-xs font-semibold" />
                            <select id="role" wire:model.live="role" class="mt-1 block w-full text-sm rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white disabled:opacity-75 disabled:bg-gray-100 dark:disabled:bg-gray-800" {{ $this->isDemoTenant ? 'disabled' : '' }}>
                                @foreach($roles as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            <x-input-error for="role" class="mt-1 text-xs" />
                        </div>

                        <!-- Estado de la Cuenta (Activo / Inactivo) -->
                        <div>
                            <x-label for="is_active" value="{{ __('Estado de la Cuenta *') }}" class="text-xs font-semibold" />
                            <select id="is_active" wire:model.live="is_active" class="mt-1 block w-full text-sm rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white disabled:opacity-75 disabled:bg-gray-100 dark:disabled:bg-gray-800" {{ $this->isDemoTenant ? 'disabled' : '' }}>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo (Por default para clientes preferenciales)</option>
                            </select>
                            <x-input-error for="is_active" class="mt-1 text-xs" />
                        </div>

                        @if(!$this->isDemoTenant)
                            <!-- Password -->
                            <div class="sm:col-span-2">
                                <x-label for="password" value="{{ __('Contraseña') }}" class="text-xs font-semibold" />
                                <x-input id="password" type="password" class="mt-1 block w-full text-sm" wire:model="password" placeholder="{{ $userId ? 'Dejar en blanco para mantener la actual' : 'Mínimo 8 caracteres' }}" />
                                <x-input-error for="password" class="mt-1 text-xs" />
                            </div>
                        @endif
                    </div>

                    @if($role === 'client')
                        <div class="p-3.5 bg-indigo-50/70 dark:bg-indigo-950/40 rounded-xl border border-indigo-200 dark:border-indigo-800 text-xs text-indigo-900 dark:text-indigo-200 space-y-1">
                            <div class="font-bold flex items-center gap-1.5">
                                <span>👤 Perfil de Cliente Preferencial:</span>
                            </div>
                            <p class="text-[11px] text-indigo-700 dark:text-indigo-300">
                                Los usuarios con rol de cliente solo acceden a su portal privado para ver sus propios vehículos, presupuestos, trabajos y pagos. Por defecto se mantienen <strong>inactivos</strong> hasta que decidas habilitar su acceso.
                            </p>
                        </div>
                    @else
                    <!-- ASIGNACIÓN DE ÁREAS (KANBAN) -->
                    <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">
                                    🏢 Áreas con Acceso en el Tablero Kanban
                                </label>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    El usuario solo verá en su Kanban las columnas de las áreas que tenga marcadas.
                                </p>
                            </div>
                            @if(!$this->isDemoTenant)
                                <div class="flex gap-1.5">
                                    <button type="button" wire:click="selectAllAreas" class="px-2 py-1 text-xs font-semibold rounded bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200">
                                        Todas
                                    </button>
                                    <button type="button" wire:click="clearAreas" class="px-2 py-1 text-xs font-semibold rounded bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200">
                                        Ninguna
                                    </button>
                                </div>
                            @endif
                        </div>

                        @if ($role === 'admin')
                            <div class="p-3 bg-purple-50 dark:bg-purple-950/30 rounded-xl text-xs text-purple-700 dark:text-purple-300 font-medium">
                                ★ Los administradores tienen acceso completo a todas las áreas del taller automáticamente.
                            </div>
                        @else
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-2">
                                @forelse($areas as $area)
                                    <label class="flex items-center gap-2.5 p-2.5 rounded-xl border border-gray-200 dark:border-gray-700 {{ $this->isDemoTenant ? 'opacity-80 cursor-default' : 'hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer' }} select-none">
                                        <input type="checkbox"
                                               wire:model="selectedAreaIds"
                                               value="{{ $area->id }}"
                                               {{ $this->isDemoTenant ? 'disabled' : '' }}
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-4 h-4 disabled:opacity-60">
                                        <span class="text-xs font-bold text-gray-800 dark:text-gray-200">{{ $area->name }}</span>
                                        @if ($area->is_delivery)
                                            <span class="text-[10px] text-emerald-600 font-bold ml-auto">🔑 Entrega</span>
                                        @endif
                                    </label>
                                @empty
                                    <p class="text-xs text-gray-400 italic col-span-2">Aún no hay áreas registradas para este taller.</p>
                                @endforelse
                            </div>
                        @endif
                    </div>

                    <!-- Permissions -->
                    <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                        <label class="flex items-center gap-2 {{ $this->isDemoTenant ? 'opacity-80 cursor-default' : 'cursor-pointer' }} select-none">
                            <input type="checkbox" wire:model="can_view_contact_info" {{ $this->isDemoTenant ? 'disabled' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-4 h-4 disabled:opacity-60" />
                            <span class="text-xs text-gray-700 dark:text-gray-300 font-medium">Permitir ver datos de contacto del cliente (Teléfono y Correo) en órdenes</span>
                        </label>
                    </div>
                    @endif
                </div>
            </x-slot>

            <x-slot name="footer">
                @if($this->isDemoTenant)
                    <x-secondary-button wire:click="$set('managingUserObject', false)">
                        {{ __('Cerrar') }}
                    </x-secondary-button>
                @else
                    <x-secondary-button wire:click="$set('managingUserObject', false)">
                        {{ __('Cancelar') }}
                    </x-secondary-button>

                    <x-button class="ms-3 bg-indigo-600 hover:bg-indigo-700" wire:click="save">
                        {{ __('Guardar Usuario') }}
                    </x-button>
                @endif
            </x-slot>
        </x-dialog-modal>

        <!-- Delete User Confirmation Modal -->
        <x-confirmation-modal wire:model="confirmingUserDeletion">
            <x-slot name="title">
                {{ __('Eliminar Usuario') }}
            </x-slot>

            <x-slot name="content">
                {{ __('¿Estás seguro de que deseas eliminar este usuario? Esta acción es irreversible.') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('confirmingUserDeletion', false)">
                    {{ __('Cancelar') }}
                </x-secondary-button>

                <x-danger-button class="ms-3" wire:click="deleteUser">
                    {{ __('Eliminar Usuario') }}
                </x-danger-button>
            </x-slot>
        </x-confirmation-modal>
    </div>
</div>
