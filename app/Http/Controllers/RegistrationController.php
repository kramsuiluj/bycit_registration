<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\School;
use App\Rules\EmptyOrAlpha;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Rap2hpoutre\FastExcel\FastExcel;

class RegistrationController extends Controller
{
    public function index()
    {
        return view('registrations.index', [
            'registrations' => Registration::latest()->filter(request(['school', 'confirmed']))->paginate(50)->withQueryString(),
            'totalRegistrations' => Registration::latest()->filter(request(['school', 'confirmed']))->count(),
            'schools' => School::all()
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

        $attributes = request()->validate([
            'lastname' => ['required', 'max:255', 'regex:/^[a-zA-zÑñ\s]+$/'],
            'firstname' => ['required', 'max:255', 'regex:/^[a-zA-zÑñ\s]+$/'],
            'middleinitial' => ['max:2', new EmptyOrAlpha],
            'school' => ['required', Rule::in($schoolIDs)],
            'type' => ['required', Rule::in($types)]
        ]);

        Registration::create([
            'school_id' => $attributes['school'],
            'lastname' => $attributes['lastname'],
            'firstname' => $attributes['firstname'],
            'middle_initial' => $attributes['middleinitial'],
            'type' => $attributes['type'],
            'date_registered' => Carbon::now()
        ]);

        return redirect(route('registrations.create'))->with('success', 'Registration Successful!');
    }

    public function update(Registration $registration)
    {
        if ($registration->confirmed === 'yes') {
            $registration->update([
                'confirmed' => 'no'
            ]);
        } else {
            if ($registration->confirmed === 'no') {
                $registration->update([
                    'confirmed' => 'yes'
                ]);
            }
        }

        return redirect()->back()->with('success', 'You have successfully updated the confirmation status.');
    }

    public function destroy(Registration $registration)
    {
        $registration->delete();

        return redirect(route('registrations.index'))->with('success', 'You have successfully deleted the record of the selected participant.');
    }

    public function export()
    {
        $registrations = Registration::latest()->filter(request(['school', 'confirmed']))->get();

        $list = collect($registrations)->map(function ($registration) {
            $registration['school'] = $registration->school->name;
            return $registration->only(['school', 'lastname', 'firstname', 'middle_initial', 'type', 'confirmed', 'date_registered']);
        });


        return (new FastExcel($list))
            ->download(Carbon::now()->toDateTimeString() . '-participants.xlsx', function ($list) {
            return [
                'School' => $list['school'],
                'Last Name' => $list['lastname'],
                'First Name' => $list['firstname'],
                'Middle Initial' => $list['middle_initial'],
                'Type' => $list['type'],
                'Confirmed' => $list['confirmed'],
                'Date Registered' => $list['date_registered']
            ];
        });
    }
}
