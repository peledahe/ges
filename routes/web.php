<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/rastreo', \App\Livewire\PublicTracking::class)->name('tracking');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'tenant', // Apply tenant scope
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/reception', \App\Livewire\ReceptionForm::class)->name('reception.create');
    Route::get('/workflow', \App\Livewire\WorkshopBoard::class)->name('workflow.index');
});
