<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\EstablishmentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/eventos/{event}/guia', [EventController::class, 'guide'])->name('events.guide');

Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

Route::get('/establishments', [EstablishmentController::class, 'index'])->name('establishments.index');
Route::get('/establishments/{establishment}', [EstablishmentController::class, 'show'])->name('establishments.show');

Route::get('/admin', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])
    ->middleware('throttle:5,1')
    ->name('admin.login.submit');

Route::middleware('admin')->prefix('admin')->group(function () {
    Route::get('/painel', [AdminAuthController::class, 'painel'])->name('admin.painel');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::match(['put', 'patch'], '/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');

    Route::get('/establishments/create', [EstablishmentController::class, 'create'])->name('establishments.create');
    Route::post('/establishments', [EstablishmentController::class, 'store'])->name('establishments.store');
    Route::get('/establishments/{establishment}/edit', [EstablishmentController::class, 'edit'])->name('establishments.edit');
    Route::match(['put', 'patch'], '/establishments/{establishment}', [EstablishmentController::class, 'update'])->name('establishments.update');
    Route::delete('/establishments/{establishment}', [EstablishmentController::class, 'destroy'])->name('establishments.destroy');
});
