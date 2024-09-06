<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tutor>
 */
class TutorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'avatar' => $this->faker->imageUrl(),
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'hourly_rate' => $this->faker->randomFloat(2, 20, 100), // Random float between 20 and 100
            'bio' => $this->faker->text(200),
            'subjects' => $this->faker->words(3), // Example: ["Math", "Science", "English"]
        ];
    }
}
