<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Other>
 */
class OtherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'course' => $this->faker->randomElement(['BSIT', 'BSCS', 'BLIS', 'BSIS']),
            'year' => $this->faker->randomElement(['1', '2', '3', '4']),
            'section' => strtoupper($this->faker->words(1, true)),
        ];
    }
}
