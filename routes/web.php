<?php

use App\Models\Registration;
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
        Route::get('/attendance', [RegistrationController::class, 'indexAttendance'])->name('registrations.indexAttendance');
        Route::post('/export', [RegistrationController::class, 'export'])->name('export');
        Route::patch('/{registration}', [RegistrationController::class, 'update'])->name('update');
        Route::patch('/first/{registration}', [RegistrationController::class, 'updateFirstDay'])->name('updateFirstDay');
        Route::patch('/second/{registration}', [RegistrationController::class, 'updateSecondDay'])->name('updateSecondDay');
        Route::delete('/{registration}/delete', [RegistrationController::class, 'destroy'])->name('destroy');
        Route::get('/count-sizes', function(){
            $registrations = Registration::all();
            $sizes = ['xsmall' => 0,'small' => 0, 'medium' => 0, 'large' => 0, 'xlarge' => 0, 'xxlarge' => 0, 'xxxlarge' => 0];
            $sizes['xsmall'] = $registrations->where('tshirt', '1')->count();
            $sizes['small'] = $registrations->where('tshirt', '2')->count();
            $sizes['medium'] = $registrations->where('tshirt', '3')->count();
            $sizes['large'] = $registrations->where('tshirt', '4')->count();
            $sizes['xlarge'] = $registrations->where('tshirt', '5')->count();
            $sizes['xxlarge'] = $registrations->where('tshirt', '6')->count();
            $sizes['xxxlarge'] = $registrations->where('tshirt', '7')->count();
            $sizes['xxxxlarge'] = $registrations->where('tshirt', '8')->count();

            dd($sizes);
        });
    });

    Route::delete('/logout', [SessionController::class, 'logout'])->name('logout');
});

Route::get('/qrcode', function () {
    return view('qr');
});
