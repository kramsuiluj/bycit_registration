<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Registration>
 */
class RegistrationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'school_id' => $this->faker->numberBetween(1,6),
            'lastname' => $this->faker->lastName,
            'firstname' => $this->faker->firstName,
            'middle_initial' => strtoupper($this->faker->randomLetter),
            'type' => $this->faker->randomElement(['Student', 'Teacher']),
            'nickname' => $this->faker->firstName,
            'paid' => $this->faker->randomElement(['yes', 'no']),
            'firstDay' => $this->faker->randomElement(['yes', 'no']),
            'secondDay' => $this->faker->randomElement(['yes', 'no']),
            'tshirt' => $this->faker->numberBetween(1,8),
            'date_registered' => Carbon::now()
        ];
    }
}

