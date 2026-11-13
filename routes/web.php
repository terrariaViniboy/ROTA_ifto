<?php

use App\Http\Controllers\EstablishmentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/eventos/{event}/guia', [EventController::class, 'guide'])->name('events.guide');

Route::resource('events', EventController::class);
Route::resource('establishments', EstablishmentController::class);
