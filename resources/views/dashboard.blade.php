<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @switch($role)
        @case('admin')
            @include('dashboard.admin')
            @break

        @case('encargado_area')
            @include('dashboard.area-manager')
            @break

        @case('recepcion')
            @include('dashboard.reception')
            @break

        @case('presupuestos')
            @include('dashboard.budget')
            @break

        @case('client')
            @include('dashboard.client')
            @break

        @default
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                        <p class="text-gray-900 dark:text-white">Bienvenido al sistema de gestión de taller</p>
                    </div>
                </div>
            </div>
    @endswitch
</x-app-layout>
