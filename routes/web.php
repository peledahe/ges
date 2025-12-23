<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/rastreo', \App\Livewire\PublicTracking::class)->name('rastreo');
Route::get('/tracking', \App\Livewire\PublicTracking::class)->name('tracking'); // Alias

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
});
