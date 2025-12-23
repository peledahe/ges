<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        
        <!-- Progress Bar -->
        <div class="bg-gray-200 h-2 w-full">
            <div class="bg-indigo-600 h-2 transition-all duration-300 ease-in-out" 
                 style="width: <?php echo e(($currentStep / $totalSteps) * 100); ?>%">
            </div>
        </div>

        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
            
            <!-- Step Indicators -->
            <div class="flex justify-between mb-8">
                <div class="text-xs <?php echo e($currentStep >= 1 ? 'text-indigo-600 font-bold' : 'text-gray-400'); ?>">1. Vehículo y Cliente</div>
                <div class="text-xs <?php echo e($currentStep >= 2 ? 'text-indigo-600 font-bold' : 'text-gray-400'); ?>">2. Estado de Recepción</div>
                <div class="text-xs <?php echo e($currentStep >= 3 ? 'text-indigo-600 font-bold' : 'text-gray-400'); ?>">3. Daños y Fotos</div>
                <div class="text-xs <?php echo e($currentStep >= 4 ? 'text-indigo-600 font-bold' : 'text-gray-400'); ?>">4. Confirmación</div>
            </div>

            <form wire:submit.prevent="submit">
                
                <!-- STEP 1: VEHICLE & CLIENT -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentStep === 1): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Search Section -->
                        <div class="col-span-1 md:col-span-2">
                            <label class="block font-medium text-sm text-gray-700">Buscar Placa</label>
                            <div class="flex gap-2">
                                <input wire:model="search_plate" type="text" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full uppercase" placeholder="ABC-123">
                                <button wire:click.prevent="searchVehicle" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Buscar</button>
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['search_plate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <!-- Vehicle Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Datos del Vehículo</h3>
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Tipo de Vehículo</label>
                                    <select wire:model.live="type" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                                        <option value="">Seleccione</option>
                                        <option value="sedan">Sedan</option>
                                        <option value="hashback">Hatchback</option>
                                        <option value="suv">Camioneta (SUV)</option>
                                        <option value="pickup">Pickup</option>
                                        <option value="pickup_double">Pickup Doble Cabina</option>
                                    </select>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Placa</label>
                                    <input wire:model="plate" type="text" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full uppercase">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['plate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
                                        <label class="block font-medium text-sm text-gray-700">Puertas</label>
                                        <input wire:model="doors_qty" type="number" min="2" max="5" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['doors_qty'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['owner_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['mileage'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['fuel_level'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                        </div>

                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <!-- STEP 2: CHECKLIST -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentStep === 2): ?>
                    <div class="space-y-6">
                        <h3 class="text-lg font-medium text-gray-900">Listado de Recepción</h3>
                        <p class="text-sm text-gray-500">Marque el estado de cada componente.</p>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(empty($checklistItems)): ?>
                            <div class="bg-yellow-100 p-4 rounded text-yellow-800">No hay plantilla de checklist configurada.</div>
                        <?php else: ?>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $checklistItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                                        <h4 class="font-bold text-gray-700 mb-3 border-b"><?php echo e($section); ?></h4>
                                        <div class="space-y-3">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item => $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="flex justify-between items-center text-sm">
                                                    <span class="text-gray-600"><?php echo e($item); ?></span>
                                                    <div class="flex space-x-1">
                                                        <!-- Buttons act as radio inputs -->
                                                        <button type="button" 
                                                            wire:click="$set('checklistItems.<?php echo e($section); ?>.<?php echo e($item); ?>', 'correct')"
                                                            class="w-6 h-6 rounded-full flex items-center justify-center border <?php echo e($status === 'correct' ? 'bg-green-500 border-green-600 text-white' : 'bg-white border-gray-300 text-gray-400 hover:bg-gray-100'); ?>" 
                                                            title="Correcto">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                        </button>
                                                        
                                                        <button type="button" 
                                                            wire:click="$set('checklistItems.<?php echo e($section); ?>.<?php echo e($item); ?>', 'wear')"
                                                            class="w-6 h-6 rounded-full flex items-center justify-center border <?php echo e($status === 'wear' ? 'bg-yellow-500 border-yellow-600 text-white' : 'bg-white border-gray-300 text-gray-400 hover:bg-gray-100'); ?>" 
                                                            title="Desgaste">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                        </button>

                                                        <button type="button" 
                                                            wire:click="$set('checklistItems.<?php echo e($section); ?>.<?php echo e($item); ?>', 'missing')"
                                                            class="w-6 h-6 rounded-full flex items-center justify-center border <?php echo e($status === 'missing' ? 'bg-red-500 border-red-600 text-white' : 'bg-white border-gray-300 text-gray-400 hover:bg-gray-100'); ?>" 
                                                            title="Falta/Malo">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                
                <!-- STEP 3: DAMAGES & PHOTOS -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentStep === 3): ?>
                     <!-- Placeholder for Image Mapper -->
                    <div class="space-y-6 text-center py-5" 
                         wire:ignore
                         wire:key="canvas-<?php echo e($this->vehicleImage); ?>"
                         x-data="{
                            isDrawing: false,
                            ctx: null,
                            canvas: null,
                            image: null,
                            imageSrc: '<?php echo e($this->vehicleImage ? asset('img/vehicle-types/' . $this->vehicleImage) : ''); ?>',
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
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->vehicleImage): ?>
                            <div class="relative inline-block border border-gray-300 shadow-sm rounded">
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
                                <button type="button" @click="clearCanvas" class="text-xs bg-red-100 text-red-700 px-3 py-1 rounded hover:bg-red-200">
                                    Borrar Marcas
                                </button>
                                <!-- Explicit Save Button for Safety -->
                                <button type="button" @click="saveCanvas" class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded hover:bg-green-200">
                                    Guardar Cambios
                                </button>
                            </div>
                        <?php else: ?>
                            <svg class="mx-auto h-24 w-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <p class="mt-1 text-sm text-gray-500">Seleccione un tipo de vehículo válido para ver el diagrama.</p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Diagrama de Daños</h3>
                        <p class="mt-1 text-sm text-gray-500">Dibuje las marcas de daños directamente sobre la imagen. <strong>¡No olvide guardar!</strong></p>
                    </div>

                    <div class="mt-6 border-t pt-6">
                        <label class="block font-medium text-sm text-gray-700 mb-2">Solicitud de Reparación / Fallas Reportadas</label>
                        <textarea wire:model="notes" rows="4" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full" placeholder="Describa los problemas reportados por el cliente o los servicios a realizar..."></textarea>
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
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($photos): ?>
                            <div class="flex gap-2 mt-4 overflow-x-auto">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <img src="<?php echo e($photo->temporaryUrl()); ?>" class="h-20 w-20 object-cover rounded shadow">
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                
                <!-- STEP 4: REVIEW -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentStep === 4): ?>
                    <div class="space-y-6">
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Resumen de Recepción</h3>
                            
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Vehículo</dt>
                                    <dd class="mt-1 text-sm text-gray-900"><?php echo e($brand); ?> <?php echo e($model); ?> <?php echo e($year); ?> - <?php echo e($plate); ?></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Cliente</dt>
                                    <dd class="mt-1 text-sm text-gray-900"><?php echo e($owner_name); ?> (<?php echo e($owner_phone); ?>)</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Kilometraje</dt>
                                    <dd class="mt-1 text-sm text-gray-900"><?php echo e(number_format($mileage)); ?> km</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Combustible</dt>
                                    <dd class="mt-1 text-sm text-gray-900"><?php echo e($fuel_level); ?></dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <!-- Navigation Buttons -->
                <div class="flex items-center justify-between mt-8 border-t pt-4">
                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentStep > 1): ?>
                        <button type="button" wire:click="previousStep" class="bg-gray-200 text-gray-700 px-4 py-2 rounded shadow-sm hover:bg-gray-300">
                            Atrás
                        </button>
                    <?php else: ?>
                        <div></div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentStep < $totalSteps): ?>
                        <button type="button" 
                                wire:click="nextStep" 
                                @click="$dispatch('step-saved')"
                                class="bg-indigo-600 text-white px-4 py-2 rounded shadow-sm hover:bg-indigo-700">
                            Siguiente
                        </button>
                    <?php else: ?>
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
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

            </form>
        </div>
    </div>
</div>
<?php /**PATH /home/pdaniels/desarrollo/ges/resources/views/livewire/reception-form.blade.php ENDPATH**/ ?>