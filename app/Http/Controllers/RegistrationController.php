<?php

namespace App\Http\Controllers;

use App\Http\Middleware\RemoveEmptyGetRequests;
use App\Models\Registration;
use App\Models\School;
use App\Rules\EmptyOrAlpha;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Rap2hpoutre\FastExcel\FastExcel;

class RegistrationController extends Controller
{
    public function __construct()
    {
        $this->middleware(RemoveEmptyGetRequests::class)->only('index');
    }

    public function index()
    {
        $registrations = Registration::latest()->filter(request(['school', 'paid', 'type']))->paginate(50)->withQueryString();

        return view('registrations.index', [
            'registrations' => $registrations,
            'totalRegistrations' => Registration::latest()->filter(request(['school', 'paid', 'type']))->count(),
            'schools' => School::all(),
            'types' => ['Student', 'Teacher'],

        ]);
    }

    public function create()
    {
        return view('registrations.create', [
            'schools' => School::all()
        ]);
    }

    public function store()
    {
        $types = ['Student', 'Teacher'];
        $schoolIDs = School::get('id')->pluck('id');
        $sizes = ['XS', 'S', 'M', 'L', 'XL', '2XL', '3XL', '4XL'];

        $attributes = request()->validate([
            'lastname' => ['required', 'max:255', 'regex:/^[a-zA-zÑñ\s]+$/'],
            'firstname' => ['required', 'max:255', 'regex:/^[a-zA-zÑñ\s]+$/'],
            'middleinitial' => ['max:2', new EmptyOrAlpha],
            'school' => ['required', Rule::in($schoolIDs)],
            'type' => ['required', Rule::in($types)],
            'size' => ['required', Rule::in($sizes)]
        ]);

        Registration::create([
            'school_id' => $attributes['school'],
            'lastname' => $attributes['lastname'],
            'firstname' => $attributes['firstname'],
            'middle_initial' => $attributes['middleinitial'],
            'type' => $attributes['type'],
            'tshirt' => $attributes['size'],
            'date_registered' => Carbon::now()
        ]);

        return redirect(route('registrations.create'))->with('success', 'Registration Successful!');
    }

    public function update(Registration $registration)
    {
        if ($registration->paid === 'yes') {
            $registration->update([
                'paid' => 'no'
            ]);
        } else {
            if ($registration->paid === 'no') {
                $registration->update([
                    'paid' => 'yes'
                ]);
            }
        }

        return redirect()->back()->with('success', 'You have successfully updated the payment status.');
    }

    public function updateFirstDay(Registration $registration)
    {
        if ($registration->firstDay === 'yes') {
            $registration->update([
                'firstDay' => 'no'
            ]);
        } else {
            if ($registration->firstDay === 'no') {
                $registration->update([
                    'firstDay' => 'yes'
                ]);
            }
        }

        return redirect()->back()->with('success', 'You have successfully updated the attendance status.');
    }

    public function updateSecondDay(Registration $registration)
    {
        if ($registration->secondDay === 'yes') {
            $registration->update([
                'secondDay' => 'no'
            ]);
        } else {
            if ($registration->secondDay === 'no') {
                $registration->update([
                    'secondDay' => 'yes'
                ]);
            }
        }

        return redirect()->back()->with('success', 'You have successfully updated the attendance status.');
    }

//    public function updatePayment(Registration $registration)
//    {
//        if ($registration->paid === 'yes') {
//            $registration->update([
//                'paid' => 'no'
//            ]);
//        } else {
//            if ($registration->paid === 'no') {
//                $registration->update([
//                    'paid' => 'yes'
//                ]);
//            }
//        }
//
//        return redirect()->back()->with('success', 'You have successfully updated the confirmation status.');
//    }

    public function destroy(Registration $registration)
    {
        $registration->delete();

        return redirect(route('registrations.index'))->with('success', 'You have successfully deleted the record of the selected participant.');
    }

    public function export()
    {
        $registrations = Registration::latest()->filter(request(['school', 'paid', 'type']))->get();

        $list = collect($registrations)->map(function ($registration) {
            $registration['school'] = $registration->school->name;
            return $registration->only(['school', 'lastname', 'firstname', 'middle_initial', 'type', 'paid', 'date_registered']);
        });


        return (new FastExcel($list))
            ->download(Carbon::now()->toDateTimeString() . '-participants.xlsx', function ($list) {
                return [
                    'School' => $list['school'],
                    'Last Name' => $list['lastname'],
                    'First Name' => $list['firstname'],
                    'Middle Initial' => $list['middle_initial'],
                    'Type' => $list['type'],
                    'paid' => $list['paid'],
                    'Date Registered' => $list['date_registered']
                ];
            });
    }
}
