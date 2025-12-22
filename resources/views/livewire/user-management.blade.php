<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Header & Toolbar -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Gestión de Usuarios') }}
            </h2>
            <div class="flex space-x-2">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar usuarios..." class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <button wire:click="create" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    Nuevo Usuario
                </button>
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Rol</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Área</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Datos de Contacto</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($users as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $user->role === 'manager' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $user->role === 'reception' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $user->role === 'client' ? 'bg-gray-100 text-gray-800' : '' }}
                                ">
                                    {{ $roles[$user->role] ?? ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{ $user->area->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($user->can_view_contact_info)
                                    <span class="text-green-600 font-bold">✓ Permitido</span>
                                @else
                                    <span class="text-gray-400">✗ Restringido</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="edit({{ $user->id }})" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600 mr-3">Editar</button>
                                <button wire:click="confirmDeletion({{ $user->id }})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-600">Eliminar</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No se encontraron usuarios.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-4 py-3">
                {{ $users->links() }}
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <x-dialog-modal wire:model="managingUserObject">
            <x-slot name="title">
                {{ $userId ? 'Editar Usuario' : 'Nuevo Usuario' }}
            </x-slot>

            <x-slot name="content">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Name -->
                    <div class="col-span-2">
                        <x-label for="name" value="{{ __('Nombre') }}" />
                        <x-input id="name" type="text" class="mt-1 block w-full" wire:model="name" />
                        <x-input-error for="name" class="mt-2" />
                    </div>

                    <!-- Email -->
                    <div class="col-span-2">
                        <x-label for="email" value="{{ __('Correo Electrónico') }}" />
                        <x-input id="email" type="email" class="mt-1 block w-full" wire:model="email" />
                        <x-input-error for="email" class="mt-2" />
                    </div>

                    <!-- Role -->
                    <div class="col-span-1">
                        <x-label for="role" value="{{ __('Rol') }}" />
                        <select id="role" wire:model.live="role" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            @foreach($roles as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-input-error for="role" class="mt-2" />
                    </div>

                    <!-- Area -->
                    <div class="col-span-1">
                        <x-label for="area_id" value="{{ __('Área Asignada') }}" />
                        <select id="area_id" wire:model="area_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value="">-- Ninguna --</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->id }}">{{ $area->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error for="area_id" class="mt-2" />
                    </div>
                    
                    <!-- Password -->
                    <div class="col-span-2">
                        <x-label for="password" value="{{ __('Contraseña') }}" />
                        <x-input id="password" type="password" class="mt-1 block w-full" wire:model="password" placeholder="{{ $userId ? 'Dejar en blanco para mantener actual' : '' }}" />
                        <x-input-error for="password" class="mt-2" />
                    </div>

                    <!-- Permissions -->
                     <div class="col-span-2">
                        <label class="flex items-center">
                            <x-checkbox wire:model="can_view_contact_info" />
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Permitir ver información de contacto del cliente (Teléfono/Email)</span>
                        </label>
                    </div>
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('managingUserObject', false)" wire:loading.attr="disabled">
                    {{ __('Cancelar') }}
                </x-secondary-button>

                <x-button class="ml-2" wire:click="save" wire:loading.attr="disabled">
                    {{ __('Guardar') }}
                </x-button>
            </x-slot>
        </x-dialog-modal>

        <!-- Delete User Confirmation Modal -->
        <x-confirmation-modal wire:model="confirmingUserDeletion">
            <x-slot name="title">
                {{ __('Eliminar Usuario') }}
            </x-slot>

            <x-slot name="content">
                {{ __('¿Estás seguro de que deseas eliminar este usuario? Una vez eliminado, toda su información será borrada permanentemente.') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('confirmingUserDeletion', false)" wire:loading.attr="disabled">
                    {{ __('Cancelar') }}
                </x-secondary-button>

                <x-danger-button class="ml-2" wire:click="deleteUser" wire:loading.attr="disabled">
                    {{ __('Eliminar Usuario') }}
                </x-danger-button>
            </x-slot>
        </x-confirmation-modal>
    </div>
</div>
