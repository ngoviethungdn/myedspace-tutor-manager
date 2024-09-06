<?php

namespace Database\Factories;

use App\Models\Tutor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TutorRateChange>
 */
class TutorRateChangeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tutor_id' => Tutor::factory(), // Creates a new tutor for each rate change
            'old_rate' => $this->faker->numberBetween(20, 100),
            'new_rate' => $this->faker->numberBetween(20, 100),
            'changed_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
