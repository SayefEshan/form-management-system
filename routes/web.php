<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FormController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Form routes with admin middleware
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('forms', FormController::class);
    Route::post('forms/import-json', [FormController::class, 'importJson'])->name('forms.import-json');
    Route::post('forms/{form}/update-structure', [FormController::class, 'updateStructure'])->name('forms.update-structure');
});

require __DIR__ . '/auth.php';
