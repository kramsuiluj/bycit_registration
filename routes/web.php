<?php

use App\Models\Registration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\RegistrationController;

//Route::get('/', [RegistrationController::class, 'create'])->name('registrations.create');
Route::view('/', 'test');
Route::post('/register', [RegistrationController::class, 'store'])->name('registrations.store');
Route::view('/login', 'login');
Route::post('/admin/login', [SessionController::class, 'login'])->name('admin.login');

Route::group(['middleware' => 'auth'], function () {
    Route::group(['prefix' => 'admin/registrations', 'as' => 'registrations.'], function () {
        Route::get('/', [RegistrationController::class, 'index'])->name('index');
        Route::get('/attendance/firstDay', [RegistrationController::class, 'indexAttendance1'])->name('firstDay');
        Route::get('/attendance/secondDay', [RegistrationController::class, 'indexAttendance2'])->name('secondDay');
        Route::post('/export', [RegistrationController::class, 'export'])->name('export');
        Route::get('/snack/first_day/am', [RegistrationController::class, 'first_snack_am'])->name('first_snack_am');
        Route::get('/snack/first_day/pm', [RegistrationController::class, 'first_snack_pm'])->name('first_snack_pm');
        Route::get('/snack/second_day/am', [RegistrationController::class, 'second_snack_am'])->name('second_snack_am');
        Route::get('/snack/second_day/pm', [RegistrationController::class, 'second_Snack_pm'])->name('second_snack_pm');
        Route::get('/lunch/first_day', [RegistrationController::class, 'first_lunch'])->name('first_lunch');
        Route::get('/lunch/second_day', [RegistrationController::class, 'second_lunch'])->name('second_lunch');
        Route::get("/lunch/{day}/{registration}", [RegistrationController::class, 'qr_first_lunch'])->name('qr_first_lunch');
        Route::get("/snack/{day}/{registration}", [RegistrationController::class, 'snack'])->name('snack');
        Route::patch('/{registration}', [RegistrationController::class, 'update'])->name('update');
        Route::patch('/first/{registration}', [RegistrationController::class, 'updateFirstDay'])->name('updateFirstDay');
        Route::get("/attendance/first/{registration}", [RegistrationController::class, 'qrFirst'])->name('qrFirst');
        Route::get("/attendance/second/{registration}", [RegistrationController::class, 'qrSecond'])->name('qrSecond');
        Route::patch('/second/{registration}', [RegistrationController::class, 'updateSecondDay'])->name('updateSecondDay');
        Route::delete('/{registration}/delete', [RegistrationController::class, 'destroy'])->name('destroy');
        Route::get('/food', function() {
            return view('snack.food');
        });
    });

    Route::delete('/logout', [SessionController::class, 'logout'])->name('logout');
});

Route::get('/qrcode', function () {
    return view('qr');
});
