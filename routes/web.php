<?php

use App\Models\Registration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\RegistrationController;

Route::get('/', [RegistrationController::class, 'create'])->name('registrations.create');
Route::post('/register', [RegistrationController::class, 'store'])->name('registrations.store');
Route::view('/login', 'login');
Route::post('/admin/login', [SessionController::class, 'login'])->name('admin.login');

Route::group(['middleware' => 'auth'], function () {
    Route::group(['prefix' => 'admin/registrations', 'as' => 'registrations.'], function () {
        Route::get('/', [RegistrationController::class, 'index'])->name('index');
        Route::get('/attendance/firstDay', [RegistrationController::class, 'indexAttendance1'])->name('registrations.firstDay');
        Route::get('/attendance/secondDay', [RegistrationController::class, 'indexAttendance2'])->name('registrations.secondDay');
        Route::post('/export', [RegistrationController::class, 'export'])->name('export');
        Route::patch('/{registration}', [RegistrationController::class, 'update'])->name('update');
        Route::patch('/first/{registration}', [RegistrationController::class, 'updateFirstDay'])->name('updateFirstDay');
        Route::get("/attendance/first/{registration}", [RegistrationController::class, 'qrFirst'])->name('qrFirst');
        Route::get("/attendance/second/{registration}", [RegistrationController::class, 'qrSecond'])->name('qrSecond');
        Route::patch('/second/{registration}', [RegistrationController::class, 'updateSecondDay'])->name('updateSecondDay');
        Route::delete('/{registration}/delete', [RegistrationController::class, 'destroy'])->name('destroy');
        Route::get('/test', [RegistrationController::class, 'test']);
    });

    Route::delete('/logout', [SessionController::class, 'logout'])->name('logout');
});

Route::get('/qrcode', function () {
    return view('qr');
});
