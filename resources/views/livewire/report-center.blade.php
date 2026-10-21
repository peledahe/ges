<div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Header & Controles de Exportación -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 sm:p-8 shadow-sm border border-gray-200/80 dark:border-gray-700 relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 dark:bg-indigo-950/70 text-indigo-700 dark:text-indigo-300 border border-indigo-200/80 dark:border-indigo-800/60 mb-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Centro de Análisis y Reportes
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                        Reportes Operativos y Financieros
                    </h1>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mt-1 max-w-xl">
                        Consulta ingresos, cobros, presupuestos y órdenes de trabajo filtrados por semana, mes, año o rango personalizado con paginación integrada.
                    </p>
                </div>
                
                <div class="flex items-center gap-3 print:hidden">
                    <button type="button" onclick="window.print()" class="inline-flex items-center px-4 py-2.5 rounded-xl text-sm font-bold text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 border border-gray-300 dark:border-gray-600 transition shadow-sm">
                        <svg class="w-4 h-4 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Imprimir Reporte
                    </button>
                    <button type="button" wire:click="resetFilters" class="inline-flex items-center px-3.5 py-2.5 rounded-xl text-sm font-semibold text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/60 border border-gray-200 dark:border-gray-700 transition">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Limpiar
                    </button>
                </div>
            </div>
        </div>

        <!-- Filtros Principales: Tipo de Reporte & Período -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-200/80 dark:border-gray-700 space-y-6 print:hidden">
            <!-- Pestañas de Tipo de Reporte -->
            <div class="flex flex-wrap items-center gap-2 border-b border-gray-100 dark:border-gray-700 pb-4">
                <button 
                    wire:click="$set('reportType', 'general')" 
                    class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition flex items-center gap-2 {{ $reportType === 'general' ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/30' : 'bg-gray-100 dark:bg-gray-700/60 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    General
                </button>
                <button 
                    wire:click="$set('reportType', 'pagos')" 
                    class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition flex items-center gap-2 {{ $reportType === 'pagos' ? 'bg-emerald-600 text-white shadow-sm shadow-emerald-600/30' : 'bg-gray-100 dark:bg-gray-700/60 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Pagos e Ingresos
                </button>
                <button 
                    wire:click="$set('reportType', 'trabajos')" 
                    class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition flex items-center gap-2 {{ $reportType === 'trabajos' ? 'bg-blue-600 text-white shadow-sm shadow-blue-600/30' : 'bg-gray-100 dark:bg-gray-700/60 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    Trabajos y Taller
                </button>
                <button 
                    wire:click="$set('reportType', 'cotizaciones')" 
                    class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition flex items-center gap-2 {{ $reportType === 'cotizaciones' ? 'bg-amber-600 text-white shadow-sm shadow-amber-600/30' : 'bg-gray-100 dark:bg-gray-700/60 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    Cotizaciones y Presupuestos
                </button>
            </div>

            <!-- Barra Unificada de Período, Fechas y Buscador (Alineación Perfecta) -->
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4 bg-gray-50/70 dark:bg-gray-900/40 p-4 rounded-2xl border border-gray-200/80 dark:border-gray-700/80">
                <!-- Selector Horizontal de Período -->
                <div class="flex-1">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1.5">
                        Período
                    </label>
                    <div class="flex flex-wrap items-center gap-2.5">
                        <div class="inline-flex items-center rounded-xl bg-gray-200/70 dark:bg-gray-900 p-1 border border-gray-300/80 dark:border-gray-700 shadow-inner h-[38px]">
                            <button type="button" wire:click="$set('period', 'today')" class="px-3 py-1 rounded-lg text-xs font-bold whitespace-nowrap transition h-full flex items-center {{ $period === 'today' ? 'bg-white dark:bg-gray-800 text-indigo-600 dark:text-indigo-400 shadow-sm border border-gray-200 dark:border-gray-700' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">Hoy</button>
                            <button type="button" wire:click="$set('period', 'week')" class="px-3 py-1 rounded-lg text-xs font-bold whitespace-nowrap transition h-full flex items-center {{ $period === 'week' ? 'bg-white dark:bg-gray-800 text-indigo-600 dark:text-indigo-400 shadow-sm border border-gray-200 dark:border-gray-700' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">Semana</button>
                            <button type="button" wire:click="$set('period', 'month')" class="px-3 py-1 rounded-lg text-xs font-bold whitespace-nowrap transition h-full flex items-center {{ $period === 'month' ? 'bg-white dark:bg-gray-800 text-indigo-600 dark:text-indigo-400 shadow-sm border border-gray-200 dark:border-gray-700' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">Mes</button>
                            <button type="button" wire:click="$set('period', 'year')" class="px-3 py-1 rounded-lg text-xs font-bold whitespace-nowrap transition h-full flex items-center {{ $period === 'year' ? 'bg-white dark:bg-gray-800 text-indigo-600 dark:text-indigo-400 shadow-sm border border-gray-200 dark:border-gray-700' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">Año</button>
                            <button type="button" wire:click="$set('period', 'custom')" class="px-3 py-1 rounded-lg text-xs font-bold whitespace-nowrap transition h-full flex items-center {{ $period === 'custom' ? 'bg-white dark:bg-gray-800 text-indigo-600 dark:text-indigo-400 shadow-sm border border-gray-200 dark:border-gray-700' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">Personalizado</button>
                        </div>

                        <!-- Indicador o Selector de Fechas -->
                        @if($period === 'custom')
                            <div class="flex items-center gap-2 bg-white dark:bg-gray-800 px-3 rounded-xl border border-indigo-200 dark:border-indigo-800 shadow-sm h-[38px]">
                                <span class="text-[11px] font-bold text-gray-500 dark:text-gray-400">Del:</span>
                                <input 
                                    type="date" 
                                    wire:model.live="startDate" 
                                    class="text-xs p-1 rounded border-0 bg-transparent text-gray-900 dark:text-white focus:ring-0">
                                <span class="text-[11px] font-bold text-gray-500 dark:text-gray-400">al:</span>
                                <input 
                                    type="date" 
                                    wire:model.live="endDate" 
                                    class="text-xs p-1 rounded border-0 bg-transparent text-gray-900 dark:text-white focus:ring-0">
                            </div>
                        @else
                            <div class="hidden sm:inline-flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400 font-mono bg-white dark:bg-gray-800 px-3 rounded-xl border border-gray-200 dark:border-gray-700 shadow-xs h-[38px]">
                                <svg class="w-3.5 h-3.5 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} — {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Buscador Nivelado a la Misma Altura que el Período -->
                <div class="w-full lg:w-96">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1.5">
                        Búsqueda Rápida
                    </label>
                    <div class="flex items-center h-[38px]">
                        <div class="relative flex-1 h-full">
                            <input 
                                type="text" 
                                wire:model.live.debounce.400ms="search" 
                                wire:keydown.enter="$refresh"
                                placeholder="Buscar placa, cliente, folio..." 
                                class="w-full h-full pl-3.5 pr-8 text-xs rounded-l-xl border border-r-0 border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            @if($search)
                                <button 
                                    type="button" 
                                    wire:click="$set('search', '')" 
                                    class="absolute right-2.5 top-2.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            @endif
                        </div>
                        <button 
                            type="button" 
                            wire:click="$refresh" 
                            title="Buscar" 
                            class="inline-flex items-center gap-1.5 px-4 h-full bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-xs font-bold rounded-r-xl border border-indigo-600 shadow-sm transition flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <span>Buscar</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Fila 3: Filtros Secundarios Dinámicos -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">
                    Filtros de Clasificación
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Estado de la Orden -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Estado de Orden</label>
                        <select wire:model.live="statusFilter" class="w-full text-xs rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2">
                            <option value="all">Todos los estados</option>
                            <option value="recibido">Recibido</option>
                            <option value="presupuesto">Presupuesto</option>
                            <option value="trabajando">Trabajando / Taller</option>
                            <option value="terminado">Terminado</option>
                            <option value="entregado">Entregado</option>
                        </select>
                    </div>

                    <!-- Estado del Pago -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Estado de Cobro</label>
                        <select wire:model.live="paymentStatusFilter" class="w-full text-xs rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2">
                            <option value="all">Todos los pagos</option>
                            <option value="pagado">Pagado Total</option>
                            <option value="parcial">Abono Parcial</option>
                            <option value="pendiente">Pendiente de Pago</option>
                        </select>
                    </div>

                    <!-- Método de Pago -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Método de Pago</label>
                        <select wire:model.live="paymentMethodFilter" class="w-full text-xs rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2">
                            <option value="all">Todos los métodos</option>
                            <option value="efectivo">Efectivo</option>
                            <option value="tarjeta">Tarjeta (Débito/Crédito)</option>
                            <option value="transferencia">Transferencia Bancaria</option>
                            <option value="cheque">Cheque</option>
                            <option value="aseguradora">Aseguradora</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>

                    <!-- Área Asignada -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Área de Taller</label>
                        <select wire:model.live="areaFilter" class="w-full text-xs rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2">
                            <option value="all">Todas las áreas</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->id }}">{{ $area->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Indicador de período activo en impresión -->
        <div class="hidden print:block bg-gray-100 p-4 rounded-xl text-sm border border-gray-300 mb-4">
            <span class="font-bold">Período de Consulta:</span> {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }} 
            | <span class="font-bold">Tipo:</span> {{ ucfirst($reportType) }}
        </div>

        <!-- KPIs Resumen Financiero y Operativo -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Total Cotizado / Presupuesto -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-200/80 dark:border-gray-700">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total Cotizado</span>
                    <div class="p-2 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-xl sm:text-2xl font-extrabold text-gray-900 dark:text-white">
                    @currencyDual($metrics['totalQuoted'])
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Presupuesto total en el período</div>
            </div>

            <!-- Total Cobrado / Ingresos -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-200/80 dark:border-gray-700">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Total Cobrado</span>
                    <div class="p-2 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-xl sm:text-2xl font-extrabold text-emerald-600 dark:text-emerald-400">
                    @currencyDual($metrics['totalPaid'])
                </div>
                <div class="text-xs text-emerald-600/80 dark:text-emerald-400/80 mt-1">Ingresos efectivos registrados</div>
            </div>

            <!-- Saldo Pendiente por Cobrar -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-200/80 dark:border-gray-700">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400">Saldo Pendiente</span>
                    <div class="p-2 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-xl sm:text-2xl font-extrabold text-amber-600 dark:text-amber-400">
                    @currencyDual($metrics['totalPending'])
                </div>
                <div class="text-xs text-amber-600/80 dark:text-amber-400/80 mt-1">Por cobrar a clientes</div>
            </div>

            <!-- Cantidad de Órdenes -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-200/80 dark:border-gray-700">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">Órdenes / Trabajos</span>
                    <div class="p-2 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                </div>
                <div class="text-xl sm:text-2xl font-extrabold text-gray-900 dark:text-white">
                    {{ $metrics['ordersCount'] }}
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Registros coincidentes</div>
            </div>
        </div>

        <!-- Desglose por Método de Pago & Estado (con fondo oscuro uniforme y alto contraste) -->
        @if(count($metrics['paymentsByMethod']) > 0 || count($metrics['ordersByStatus']) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 print:hidden">
            <!-- Pagos por Método -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-200/80 dark:border-gray-700">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-3 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Distribución de Cobros por Método
                </h3>
                <div class="space-y-2">
                    @forelse($metrics['paymentsByMethod'] as $method => $amount)
                        <div class="flex items-center justify-between text-xs sm:text-sm p-3 rounded-xl bg-gray-50 dark:bg-gray-900/60 border border-gray-200/80 dark:border-gray-700/60">
                            <span class="font-semibold text-gray-800 dark:text-gray-200 capitalize">{{ $method ?: 'No especificado' }}</span>
                            <span class="font-extrabold text-emerald-600 dark:text-emerald-400 font-mono">@currencyDual($amount)</span>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400">Sin transacciones registradas</p>
                    @endforelse
                </div>
            </div>

            <!-- Órdenes por Estado -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-200/80 dark:border-gray-700">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-3 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                    Distribución de Trabajos por Estado
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                    @forelse($metrics['ordersByStatus'] as $st => $count)
                        <div class="p-3 rounded-xl bg-gray-50 dark:bg-gray-900/60 border border-gray-200/80 dark:border-gray-700/60 text-center">
                            <div class="text-xl font-extrabold text-gray-900 dark:text-white">{{ $count }}</div>
                            <div class="text-[11px] font-semibold text-gray-600 dark:text-gray-400 capitalize mt-0.5">{{ $st }}</div>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400 col-span-3">Sin órdenes registradas</p>
                    @endforelse
                </div>
            </div>
        </div>
        @endif

        <!-- Tabla de Registros con Paginación -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200/80 dark:border-gray-700 overflow-hidden">
            <div class="p-5 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h2 class="text-base font-extrabold text-gray-900 dark:text-white">
                        Listado Detallado de Registros
                    </h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Mostrando {{ $workOrders->firstItem() ?? 0 }} a {{ $workOrders->lastItem() ?? 0 }} de {{ $workOrders->total() }} registros
                    </p>
                </div>
                <div class="text-xs text-gray-400 print:hidden font-medium">
                    Paginación activa (12 registros por página)
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50/75 dark:bg-gray-900/50 text-[11px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            <th class="py-3 px-4">Folio / Fecha</th>
                            <th class="py-3 px-4">Vehículo & Cliente</th>
                            <th class="py-3 px-4">Área</th>
                            <th class="py-3 px-4">Estado</th>
                            <th class="py-3 px-4 text-right">Cotizado</th>
                            <th class="py-3 px-4 text-right">Cobrado</th>
                            <th class="py-3 px-4 text-right">Saldo</th>
                            <th class="py-3 px-4 text-center">Método / Pago</th>
                            <th class="py-3 px-4 text-right print:hidden">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-xs">
                        @forelse($workOrders as $order)
                            <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-700/30 transition">
                                <!-- Folio & Fecha -->
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <div class="font-extrabold text-indigo-600 dark:text-indigo-400">
                                        #{{ $order->id }}
                                    </div>
                                    <div class="text-[11px] text-gray-500 dark:text-gray-400">
                                        {{ $order->created_at->format('d/m/Y H:i') }}
                                    </div>
                                </td>

                                <!-- Vehículo & Cliente -->
                                <td class="py-3 px-4">
                                    <div class="font-bold text-gray-900 dark:text-white flex items-center gap-1.5">
                                        <span class="inline-block px-1.5 py-0.5 rounded bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-300 font-mono text-[11px]">
                                            {{ $order->vehicle->plate ?? 'S/P' }}
                                        </span>
                                        <span>{{ $order->vehicle->brand ?? '' }} {{ $order->vehicle->model ?? '' }}</span>
                                    </div>
                                    <div class="text-[11px] text-gray-500 dark:text-gray-400 truncate max-w-xs">
                                        {{ $order->vehicle->owner_name ?? 'Consumidor Final' }}
                                        @if(!empty($order->vehicle->owner_phone))
                                            • {{ $order->vehicle->owner_phone }}
                                        @endif
                                    </div>
                                </td>

                                <!-- Área -->
                                <td class="py-3 px-4 whitespace-nowrap">
                                    @if($order->area)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-semibold bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-300">
                                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                            {{ $order->area->name }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-[11px]">-</span>
                                    @endif
                                </td>

                                <!-- Estado -->
                                <td class="py-3 px-4 whitespace-nowrap">
                                    @php
                                        $statusBadges = [
                                            'recibido' => 'bg-purple-100 dark:bg-purple-950/80 text-purple-700 dark:text-purple-300 border-purple-200 dark:border-purple-800',
                                            'presupuesto' => 'bg-amber-100 dark:bg-amber-950/80 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800',
                                            'trabajando' => 'bg-blue-100 dark:bg-blue-950/80 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800',
                                            'terminado' => 'bg-emerald-100 dark:bg-emerald-950/80 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
                                            'entregado' => 'bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700',
                                        ];
                                        $badgeClass = $statusBadges[$order->status] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $badgeClass }}">
                                        {{ $order->status }}
                                    </span>
                                </td>

                                <!-- Montos -->
                                <td class="py-3 px-4 text-right whitespace-nowrap font-bold text-gray-900 dark:text-white font-mono">
                                    @currencyDual($order->total_amount ?? 0)
                                </td>

                                <td class="py-3 px-4 text-right whitespace-nowrap font-bold text-emerald-600 dark:text-emerald-400 font-mono">
                                    @currencyDual($order->paid_amount ?? 0)
                                </td>

                                <td class="py-3 px-4 text-right whitespace-nowrap font-bold font-mono {{ $order->pendingPaymentAmount() > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-gray-400' }}">
                                    @currencyDual($order->pendingPaymentAmount())
                                </td>

                                <!-- Método / Cobro -->
                                <td class="py-3 px-4 text-center whitespace-nowrap">
                                    @if($order->payment_status === 'pagado')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300">
                                            Pagado ({{ ucfirst($order->payment_method ?? 'N/A') }})
                                        </span>
                                    @elseif($order->payment_status === 'parcial')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300">
                                            Abono ({{ ucfirst($order->payment_method ?? 'N/A') }})
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300">
                                            Pendiente
                                        </span>
                                    @endif
                                </td>

                                <!-- Acción -->
                                <td class="py-3 px-4 text-right whitespace-nowrap print:hidden">
                                    <a href="{{ route('workflow.index') }}" class="p-1.5 rounded-lg text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-gray-700/60 transition inline-block" title="Ver en Tablero">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <p class="font-bold text-sm">No se encontraron registros</p>
                                        <p class="text-xs text-gray-400 mt-1">Prueba seleccionando otro período, rango de fechas o eliminando filtros.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagineo -->
            @if ($workOrders->hasPages())
                <div class="p-4 border-t border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 print:hidden">
                    {{ $workOrders->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
