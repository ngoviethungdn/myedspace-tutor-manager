<?php

namespace Tests\Unit\Models;

use App\Models\Student;
use App\Models\Tutor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Unit tests for the Tutor model.
 */
class TutorTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating a tutor.
     *
     * @return void
     */
    public function test_can_create_a_tutor()
    {
        $tutor = Tutor::factory()->create();

        $this->assertDatabaseHas('tutors', [
            'id' => $tutor->id,
            'name' => $tutor->name,
            'email' => $tutor->email,
            'hourly_rate' => $tutor->hourly_rate,
        ]);
    }

    /**
     * Test updating a tutor.
     *
     * @return void
     */
    public function test_can_update_a_tutor()
    {
        $tutor = Tutor::factory()->create([
            'hourly_rate' => 50,
        ]);

        $tutor->update(['hourly_rate' => 60]);

        $this->assertDatabaseHas('tutors', [
            'id' => $tutor->id,
            'hourly_rate' => 60,
        ]);
    }

    /**
     * Test deleting a tutor.
     *
     * @return void
     */
    public function test_can_delete_a_tutor()
    {
        $tutor = Tutor::factory()->create();

        $tutor->delete();

        $this->assertDatabaseMissing('tutors', [
            'id' => $tutor->id,
        ]);
    }

    /**
     * Test that a tutor has many students.
     *
     * @return void
     */
    public function test_has_many_students()
    {
        $tutor = Tutor::factory()->create();
        $students = Student::factory()->count(3)->create();

        $tutor->students()->attach($students->pluck('id'));

        $this->assertCount(3, $tutor->students);
    }

    /**
     * Test querying tutors with search parameters.
     *
     * @return void
     */
    public function test_query_search()
    {
        $tutor1 = Tutor::factory()->create([
            'name' => 'John Doe',
            'subjects' => ['Mathematics'],
            'hourly_rate' => 100,
        ]);

        $tutor2 = Tutor::factory()->create([
            'name' => 'Jane Smith',
            'subjects' => ['Physics'],
            'hourly_rate' => 80,
        ]);

        $results = Tutor::querySearch([
            'search' => 'John',
            'subjects' => 'Mathematics',
            'min_hourly_rate' => 50,
            'max_hourly_rate' => 150,
        ])->get();

        $this->assertTrue($results->contains($tutor1));
        $this->assertFalse($results->contains($tutor2));
    }
}
