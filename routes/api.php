<?php

use App\Http\Controllers\RegistrationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/registrations/attendees', [RegistrationController::class, 'getAttendees'])->name('registrations.getAttendees');

Route::patch('/registrations/{registration}/attendance', [RegistrationController::class, 'updateAttendance'])->name('registrations.updateAttendance');
