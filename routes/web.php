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
        Route::get('/attendance', [RegistrationController::class, 'indexAttendance'])->name('registrations.indexAttendance');
        Route::post('/export', [RegistrationController::class, 'export'])->name('export');
        Route::patch('/{registration}', [RegistrationController::class, 'update'])->name('update');
        Route::patch('/first/{registration}', [RegistrationController::class, 'updateFirstDay'])->name('updateFirstDay');
        Route::patch('/second/{registration}', [RegistrationController::class, 'updateSecondDay'])->name('updateSecondDay');
        Route::delete('/{registration}/delete', [RegistrationController::class, 'destroy'])->name('destroy');
        Route::get('/count-sizes', function () {
            $reg = Registration::select('id', DB::raw("CONCAT(lastname, ' ', firstname, ' ', middle_initial) AS name"), 'school_id', 'type', 'tshirt', 'paid', 'firstDay', 'secondDay', 'date_registered', 'nickname')->orderBy('id', 'DESC')->groupBy('name')->get();
            $registrations = Registration::select('id', DB::raw("CONCAT(lastname, ' ', firstname, ' ', middle_initial) AS name"), 'school_id', 'type', 'tshirt', 'paid', 'firstDay', 'secondDay', 'date_registered', 'nickname')->orderBy('id', 'DESC')->get()->diff($reg);

            $list = collect($registrations)->map(function ($registration) {

                if ($registration->others !== null) {
                    $registration['course'] = $registration->others->course;
                    $registration['year'] = $registration->others->year;
                    $registration['section'] = $registration->others->section;
                } else {
                    $registration['course'] = '';
                    $registration['year'] = '';
                    $registration['section'] = '';
                }

                $registration['school'] = $registration->school->name;
                $registration['tshirt'] = $registration->size->name;
                return $registration->only(['id','name', 'school', 'type', 'tshirt', 'paid', 'firstDay', 'secondDay', 'date_registered', 'course', 'year', 'section', 'nickname']);
            });

            return (new FastExcel($list))
                ->download(Carbon::now()->toDateTimeString() . '-participants.xlsx', function ($list) {
                    return [
                        'School' => $list['school'],
                        "Name" => $list['name'],
                        'Nickname' => $list['nickname'],
                        'Type' => $list['type'],
                        'Course' => $list['course'],
                        'Year' => $list['year'],
                        'Section' => $list['section'],
                        'T-Shirt Size' => $list['tshirt'],
                        'Paid' => $list['paid'],
                        'Attendance (1st Day)' => $list['firstDay'],
                        'Attendance (2nd Day)' => $list['secondDay'],
                        'Date Registered' => $list['date_registered']
                    ];
                });
        });
    });

    Route::delete('/logout', [SessionController::class, 'logout'])->name('logout');
});

Route::get('/qrcode', function () {
    return view('qr');
});
