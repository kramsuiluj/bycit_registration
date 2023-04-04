<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Other;
use App\Models\Registration;
use App\Models\School;
use App\Models\Sizes;
use App\Models\User;
use Database\Factories\RegistrationFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $schools = [
            'Camarines Sur Polytechnic Colleges',
            'ACLC College of Iriga City, Inc',
            'Bicol University - Polangui Campus',
            'Ceguera Technological Colleges - Iriga City',
            'Camarines Norte State College',
            'Oliveros College Incorporated',
        ];

        $sizes = ['XS', 'S', 'M', 'L', 'XL', '2XL'];

        if (School::all()->count() === 0) {
            foreach ($schools as $school) {
                School::create([
                    'name' => $school
                ]);
            }
        }

        if(Sizes::all()->count() === 0) {
            foreach ($sizes as $size){
                Sizes::create([
                    'name' => $size
                ]);
            }
        }

        if (!User::find(1)) {
            User::create([
                'username' => 'admin',
                'password' => bcrypt('loremipsumdolorsitamet')
            ]);
        }

//        $registrations = Registration::factory(1000)->create();
//
//        foreach ($registrations as $registration) {
//            if ($registration->school_id === 1 || $registration->school_id === 2) {
//                Other::factory()->create([
//                    'registration_id' => $registration->id
//                ]);
//            }
//        }
    }
}
