<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 overflow-hidden shadow-lg rounded-lg p-8 mb-8">
            <h2 class="text-3xl font-bold text-white">Portal del Cliente</h2>
            <p class="text-blue-100 mt-2">Consulta el estado de tus vehículos</p>
        </div>

        <!-- Info Message -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg p-8">
            <div class="text-center">
                <svg class="mx-auto h-16 w-16 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white"><?php echo e($data['message']); ?></h3>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Pronto podrás ver el estado de tus vehículos y órdenes de trabajo aquí.
                </p>
                <div class="mt-8">
                    <a href="<?php echo e(route('rastreo')); ?>" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition">
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Rastrear mi Vehículo
                    </a>
                </div>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Información de Contacto</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex items-start">
                    <svg class="h-6 w-6 text-blue-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Teléfono</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Contacta con nosotros para más información</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <svg class="h-6 w-6 text-blue-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Email</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Recibirás notificaciones sobre tus órdenes</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH /home/pdaniels/desarrollo/ges/resources/views/dashboard/client.blade.php ENDPATH**/ ?>