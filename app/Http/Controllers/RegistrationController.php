<?php

namespace App\Http\Controllers;

use App\Exports\RegistrationsExport;
use Carbon\Carbon;
use App\Models\Other;
use App\Models\Sizes;
use App\Models\School;
use App\Rules\EmptyOrAlpha;
use App\Models\Registration;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Http\Middleware\RemoveEmptyGetRequests;

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
            'sizes' => Sizes::all(),
        ]);
    }

    public function indexAttendance()
    {
        return view('registrations.attendance');
    }

    public function getAttendees()
    {
        $attendees = Registration::latest('updated_at')->where('firstDay', 'yes')->take(10)->get();

        return response()->json(['attendees' => $attendees]);
    }

    public function create()
    {
        return view('registrations.create', [
            'schools' => School::all(),
            'sizes' => Sizes::all()
        ]);
    }

    public function store()
    {
        $types = ['Student', 'Teacher'];
        $schoolIDs = School::get('id')->pluck('id');
        $sizes = Sizes::get('id')->pluck('id');
        $courses = ['BSIT', 'BSCS', 'BLIS', 'BSIS'];
        $years = ['1', '2', '3'];

        $attributes = request()->validate([
            'lastname' => ['required', 'max:255', 'regex:/^[a-zA-zÑñ\s]+$/', 'min:2'],
            'firstname' => ['required', 'max:255', 'regex:/^[a-zA-zÑñ\s]+$/', 'min:2'],
            'middleinitial' => ['max:3', new EmptyOrAlpha],
            'school' => ['required', Rule::in($schoolIDs)],
            'type' => ['required', Rule::in($types)],
            'size' => ['required', Rule::in($sizes)],
            'course' => [Rule::in($courses)],
            'year' => [Rule::in($years)],
            'section' => ['min:0', 'max:1', new EmptyOrAlpha]
        ]);

        $registration = Registration::create([
            'school_id' => $attributes['school'],
            'lastname' => ucwords($attributes['lastname']),
            'firstname' => ucwords($attributes['firstname']),
            'middle_initial' => ucwords($attributes['middleinitial']),
            'type' => $attributes['type'],
            'tshirt' => $attributes['size'],
            'date_registered' => Carbon::now(),
            'nickname' => empty(request('nickname')) ? ucwords($attributes['firstname']) : ucwords(request()->nickname)
        ]);

        if (request('course') && request('year') && request('section')) {
            Other::create([
                'registration_id' => $registration->id,
                'course' => $attributes['course'],
                'year' => $attributes['year'],
                'section' => $attributes['section']
            ]);
        }

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

    public function updateAttendance(Registration $registration): JsonResponse
    {
        if ($registration->firstDay === 'no') {
            $registration->update([
                'firstDay' => 'yes'
            ]);

            return response()->json(['message' => 'Attendance Checked!', 'success' => '1', 'attendees' => Registration::latest('updated_at')->where('firstDay', 'yes')->take(10)->get()]);
        }

        if ($registration->firstDay === 'yes') {
            return response()->json(['message' => 'Participant already in attendance list.', 'success' => '0', 'attendees' => Registration::latest('updated_at')->where('firstDay', 'yes')->take(10)->get()]);
        }

        return response()->json(['message' => 'An error was encountered while checking the attendance.', 'attendees' => Registration::latest('updated_at')->where('firstDay', 'yes')->take(10)->get()]);
    }

    public function destroy(Registration $registration)
    {
        $registration->delete();

        return redirect(route('registrations.index'))->with('success', 'You have successfully deleted the record of the selected participant.');
    }

    public function export()
    {
//        return Excel::download(new RegistrationsExport(), 'registrations_list.xlsx');
        $registrations = Registration::latest()->filter(request(['school', 'paid', 'type']))->get();

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
            return $registration->only(['school', 'lastname', 'firstname', 'middle_initial', 'type', 'tshirt', 'paid', 'firstDay', 'secondDay', 'date_registered', 'course', 'year', 'section', 'nickname']);
        });

        return (new FastExcel($list))
            ->download(Carbon::now()->toDateTimeString() . '-participants.xlsx', function ($list) {
                return [
                    'School' => $list['school'],
                    'Last Name' => $list['lastname'],
                    'First Name' => $list['firstname'],
                    'Middle Initial' => $list['middle_initial'],
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
    }
}
