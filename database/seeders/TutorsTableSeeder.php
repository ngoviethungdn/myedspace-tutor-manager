<?php

namespace Database\Seeders;

use App\Models\Tutor;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class TutorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 50) as $index) {
            Tutor::create([
                'avatar' => $faker->imageUrl(200, 200, 'people'),
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'hourly_rate' => $faker->randomFloat(2, 10, 100), // Random rate between 10 and 100
                'bio' => $faker->text(200),
                'subjects' => $faker->randomElements(
                    Tutor::SUBJECTS,
                    rand(1, 4)
                ), // Pick 1 to 4 random subjects
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
