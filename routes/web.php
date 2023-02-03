<?php

use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RegistrationController::class, 'create'])->name('registrations.create');
Route::post('/register', [RegistrationController::class, 'store'])->name('registrations.store');
Route::view('/login', 'login');
Route::post('/admin/login', [SessionController::class, 'login'])->name('admin.login');

Route::group(['middleware' => 'auth'], function () {
    Route::get('admin/registrations', [RegistrationController::class, 'index'])->name('registrations.index');
    Route::post('admin/registrations/export', [RegistrationController::class, 'export'])->name('registrations.export');
    Route::patch('admin/registrations/{registration}', [RegistrationController::class, 'update'])->name('registrations.update');
    Route::delete('admin/registrations/{registration}/delete', [RegistrationController::class, 'destroy'])->name('registrations.destroy');
});
