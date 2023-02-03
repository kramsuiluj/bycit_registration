<?php

use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RegistrationController::class, 'create'])->name('registrations.create');
Route::post('/register', [RegistrationController::class, 'store'])->name('registrations.store');
