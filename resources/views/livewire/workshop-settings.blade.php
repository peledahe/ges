<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Administración del Taller') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                @if (session()->has('message'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('message') }}</span>
                    </div>
                @endif

                <form wire:submit.prevent="save">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Logo -->
                        <div class="col-span-1 md:col-span-2">
                             <label class="block text-sm font-medium text-gray-700">Logotipo del Taller</label>
                             <div class="mt-2 flex items-center space-x-6">
                                <div class="shrink-0">
                                    @if ($logo)
                                        <img class="h-16 w-16 object-cover rounded-full" src="{{ $logo->temporaryUrl() }}" alt="Logo Temporal">
                                    @elseif ($currentLogo)
                                        <img class="h-16 w-16 object-cover rounded-full" src="{{ Storage::url($currentLogo) }}" alt="Logo Actual">
                                    @else
                                        <span class="inline-block h-16 w-16 overflow-hidden rounded-full bg-gray-100">
                                            <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </span>
                                    @endif
                                </div>
                                <label class="block">
                                    <span class="sr-only">Seleccionar Archivo</span>
                                    <input type="file" wire:model="logo" class="block w-full text-sm text-slate-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-full file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-indigo-50 file:text-indigo-700
                                      hover:file:bg-indigo-100
                                    "/>
                                </label>
                            </div>
                            @error('logo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Name -->
                        <div>
                            <x-label for="name" value="{{ __('Nombre del Taller') }}" />
                            <x-input id="name" type="text" class="mt-1 block w-full" wire:model="name" />
                            <x-input-error for="name" class="mt-2" />
                        </div>

                        <!-- Admin Name -->
                        <div>
                            <x-label for="admin_name" value="{{ __('Administrador / Contacto') }}" />
                            <x-input id="admin_name" type="text" class="mt-1 block w-full" wire:model="admin_name" />
                            <x-input-error for="admin_name" class="mt-2" />
                        </div>

                        <!-- Email -->
                        <div>
                            <x-label for="email" value="{{ __('Correo Electrónico') }}" />
                            <x-input id="email" type="email" class="mt-1 block w-full" wire:model="email" />
                            <x-input-error for="email" class="mt-2" />
                        </div>

                        <!-- Phone -->
                        <div>
                            <x-label for="phone" value="{{ __('Teléfono') }}" />
                            <x-input id="phone" type="text" class="mt-1 block w-full" wire:model="phone" />
                            <x-input-error for="phone" class="mt-2" />
                        </div>

                        <!-- Address -->
                        <div class="md:col-span-2">
                            <x-label for="address" value="{{ __('Dirección') }}" />
                            <x-input id="address" type="text" class="mt-1 block w-full" wire:model="address" />
                            <x-input-error for="address" class="mt-2" />
                        </div>

                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <x-button class="ml-4" wire:loading.attr="disabled">
                            {{ __('Guardar Cambios') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
