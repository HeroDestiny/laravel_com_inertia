<?php

use App\Http\Controllers\PacienteController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Rotas para pacientes
    Route::resource('pacientes', PacienteController::class);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
