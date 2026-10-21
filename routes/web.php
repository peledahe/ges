<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/rastreo', \App\Livewire\PublicTracking::class)->name('rastreo');
Route::get('/rastreo/{tenant:slug}', \App\Livewire\PublicTracking::class)->name('rastreo.tenant');
Route::get('/tracking', \App\Livewire\PublicTracking::class)->name('tracking'); // Alias
Route::get('/tracking/{tenant:slug}', \App\Livewire\PublicTracking::class)->name('tracking.tenant'); // Alias

Route::get('/currency/{code}', function (string $code) {
    \App\Helpers\CurrencyHelper::setActiveCurrency(strtoupper($code));
    return back();
})->name('currency.switch');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'tenant', // Apply tenant scope
])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/recepcion', \App\Livewire\ReceptionForm::class)->name('recepcion');
    Route::get('/reception', \App\Livewire\ReceptionForm::class)->name('reception.create'); // Alias
    Route::get('/workflow', \App\Livewire\WorkshopBoard::class)->name('workflow.index');
    Route::get('/taller', \App\Livewire\WorkshopSettings::class)->name('taller');
    Route::get('/configuracion', \App\Livewire\WorkshopSettings::class)->name('configuracion'); // Alias
    Route::get('/users', \App\Livewire\UserManagement::class)->name('users.index');
    Route::get('/reportes', \App\Livewire\ReportCenter::class)->name('reportes.index');
    Route::get('/reports', \App\Livewire\ReportCenter::class)->name('reports.index');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'root',
])->prefix('admin')->group(function () {
    Route::get('/tenants', \App\Livewire\TenantManagement::class)->name('admin.tenants');
});

