<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Other;
use App\Models\Sizes;
use App\Models\School;
use App\Rules\EmptyOrAlpha;
use App\Models\Registration;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use App\Exports\RegistrationsExport;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Http\Middleware\RemoveEmptyGetRequests;
use App\Models\Food;

class RegistrationController extends Controller
{
    public function __construct()
    {
        $this->middleware(RemoveEmptyGetRequests::class)->only('index');
    }

    public function test()
    {
        return view('test.test');
    }

    public function index()
    {
        $registrations = Registration::latest()->filter(request(['school', 'paid', 'type', 'search']))->paginate(50)->withQueryString();

        return view('registrations.index', [
            'registrations' => $registrations,
            'totalRegistrations' => Registration::latest()->filter(request(['school', 'paid', 'type', 'search']))->count(),
            'schools' => School::all(),
            'types' => ['Student', 'Teacher'],
            'sizes' => Sizes::all(),
        ]);
    }

    public function indexAttendance1()
    {
        return view('registrations.attendance', ['day' => "1"]);
    }
    public function indexAttendance2()
    {
        return view('registrations.attendance', ['day' => "2"]);
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
        $years = ['1', '2', '3', '4'];

        $attributes = request()->validate([
            'lastname' => [
                'required', 'max:255', 'regex:/^[a-zA-zÑñ\s]+$/', 'min:2',
                Rule::unique('registrations')
                    ->where('lastname', request('lastname'))
                    ->where('firstname', request('firstname'))
                    ->where('middle_initial', request('middle_initial'))
                    ->where('tshirt', request('tshirt'))
            ],
            'firstname' => [
                'required', 'max:255', 'regex:/^[a-zA-zÑñ\s]+$/', 'min:2',
                Rule::unique('registrations')
                    ->where('lastname', request('lastname'))
                    ->where('firstname', request('firstname'))
                    ->where('middle_initial', request('middle_initial'))
                    ->where('tshirt', request('tshirt'))
            ],
            'middle_initial' => [
                'max:3', new EmptyOrAlpha,
                Rule::unique('registrations')
                    ->where('lastname', request('lastname'))
                    ->where('firstname', request('firstname'))
                    ->where('middle_initial', request('middle_initial'))
                    ->where('tshirt', request('tshirt'))
            ],
            'school' => ['required', Rule::in($schoolIDs)],
            'type' => ['required', Rule::in($types)],
            'tshirt' => [
                'required', Rule::in($sizes),
                Rule::unique('registrations')
                    ->where('lastname', request('lastname'))
                    ->where('firstname', request('firstname'))
                    ->where('middle_initial', request('middle_initial'))
                    ->where('tshirt', request('tshirt'))
            ],
            'course' => [Rule::in($courses)],
            'year' => [Rule::in($years)],
            'section' => ['min:0', 'max:1', new EmptyOrAlpha]
        ]);

        $registration = Registration::create([
            'school_id' => $attributes['school'],
            'lastname' => ucwords($attributes['lastname']),
            'firstname' => ucwords($attributes['firstname']),
            'middle_initial' => ucwords($attributes['middle_initial']),
            'type' => $attributes['type'],
            'tshirt' => $attributes['tshirt'],
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

        return redirect(route('registrations.create'))->with('success', 'Registration Successful!')->withErrors($attributes, 'registration');
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

    public function qrFirst($id)
    {
        $registration = Registration::find($id);

        if ($registration) {
            $fullname = $registration->lastname . ", " . $registration->firstname . ($registration->middle_initial != "" ? " " . $registration->middle_initial . "." : "");
            $tshirt = $registration->size->name;
            try {
                $courseSection = $registration->others->course . " " . $registration->others->year . $registration->others->section;
            } catch (Exception $e) {
                $courseSection = "";
            }
            $message = $registration->firstDay == 'yes' ? "Registration already Satisfied First Day Attendance" : "Registration Attendance Successful";
            $registration->update(['firstDay' => 'yes']);

            return response()->json(['status' => 200, 'full_name' => $fullname, 'course' => $courseSection, 'tshirt' => $tshirt, 'message' => $message]);
        } else {
            return response()->json(['status' => 404, 'message' => "Student not found"]);
        }
    }

    public function qrSecond($id)
    {
        $registration = Registration::find($id);

        if ($registration) {
            $fullname = $registration->lastname . ", " . $registration->firstname . ($registration->middle_initial != "" ? " " . $registration->middle_initial . "." : "");
            $tshirt = $registration->size->name;
            try {
                $courseSection = $registration->others->course . " " . $registration->others->year . $registration->others->section;
            } catch (Exception $e) {
                $courseSection = "";
            }
            $message = $registration->secondDay == 'yes' ? "Attendance Already Recorded" : "Attendance Recorded";
            $registration->update(['secondDay' => 'yes']);

            return response()->json(['status' => 200, 'full_name' => $fullname, 'course' => $courseSection, 'tshirt' => $tshirt, 'message' => $message]);
        } else {
            return response()->json(['status' => 404, 'message' => "Student not found"]);
        }
    }

    public function qr_first_lunch($day, $id)
    {
        $registration = Registration::find($id);
        if ($registration) {
            $fullname = $registration->lastname . ", " . $registration->firstname . ($registration->middle_initial != "" ? " " . $registration->middle_initial . "." : "");
            $tshirt = $registration->size->name;
            try {
                $courseSection = $registration->others->course . " " . $registration->others->year . $registration->others->section;
            } catch (Exception $e) {
                $courseSection = "";
            }
            $message = $registration->secondDay == 'yes' ? "Attendance Already Recorded" : "Attendance Recorded";

            $food = Food::where('registration', $id)->first();

            if (!$food) {
                Food::create([
                    'registration' => $id,
                    $day == 1 ? 'first_lunch' : 'second_lunch' => true
                ]);
                $message = "Lunch Recorded";
            } else {
                if($day == 1){
                    if ($food->first_lunch == true) {
                        $message = "Lunch already served";
                    } else {
                        $food->first_lunch = true;
                        $food->save();
                        $message = "Lunch Recorded";
                    }
                }
                else{
                    if ($food->second_day == true) {
                        $message = "Lunch already served";
                    } else {
                        $food->second_day = true;
                        $food->save();
                        $message = "Lunch Recorded";
                    }
                }
                
            }
        }

        return response()->json(['status' => 200, 'full_name' => $fullname, 'course' => $courseSection, 'tshirt' => $tshirt, 'message' => $message]);
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

    public function first_lunch()
    {
        return view('snack.lunch', ['day' => 1]);
    }
    public function second_lunch()
    {
        return view('snack.lunch', ['day' => 2]);
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
            return $registration->only(['id', 'school', 'lastname', 'firstname', 'middle_initial', 'type', 'tshirt', 'paid', 'firstDay', 'secondDay', 'date_registered', 'course', 'year', 'section', 'nickname']);
        });

        return (new FastExcel($list))
            ->download(Carbon::now()->toDateTimeString() . '-participants.xlsx', function ($list) {
                return [
                    'id' => $list['id'],
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

    protected function generateQrCode($participant)
    {
        $filename = $participant->id . '.png';
    }
}
