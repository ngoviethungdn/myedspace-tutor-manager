<?php

namespace Database\Seeders;

use App\Models\Student;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class StudentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $index) {
            Student::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'grade_level' => $faker->numberBetween(1, 12), // Random grade level between 1 and 12
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
