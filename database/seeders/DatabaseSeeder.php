<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Registration;
use App\Models\School;
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
            'Camarines Sur Polytechnic Colleges - Buhi Campus',
            'Ceguera Technological Colleges',
            'ACLC College of Iriga',
            'Bicol University - Polangui',
            'St. Bridget School',
            'Oliveros College Inc.'
        ];

        if (School::all()->count() === 0) {
            foreach ($schools as $school) {
                School::create([
                    'name' => $school
                ]);
            }
        }

        if (!User::find(1)) {
            User::create([
                'username' => 'admin',
                'password' => bcrypt('!password')
            ]);
        }

        Registration::factory(1000)->create();
    }
}
