<?php

namespace Tests\Unit\Models;

use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Unit tests for the Student model.
 */
class StudentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating a student.
     * Ensures that a student can be created and the correct data is stored in the database.
     *
     * @return void
     */
    public function test_can_create_student()
    {
        // Create a student using the factory
        $student = Student::factory()->create();

        // Assert that the student's data is stored in the database
        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'name' => $student->name,
            'email' => $student->email,
            'grade_level' => $student->grade_level,
        ]);
    }

    /**
     * Test updating a student.
     * Ensures that a student can be updated and the changes are reflected in the database.
     *
     * @return void
     */
    public function test_can_update_student()
    {
        // Create a student
        $student = Student::factory()->create();

        // Update the student's name and grade level
        $student->update([
            'name' => 'Updated Name',
            'grade_level' => 10,
        ]);

        // Assert that the updated data is stored in the database
        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'name' => 'Updated Name',
            'grade_level' => 10,
        ]);
    }

    /**
     * Test deleting a student.
     * Ensures that a student can be deleted and is no longer present in the database.
     *
     * @return void
     */
    public function test_can_delete_student()
    {
        // Create a student
        $student = Student::factory()->create();

        // Delete the student
        $student->delete();

        // Assert that the student is missing from the database
        $this->assertDatabaseMissing('students', [
            'id' => $student->id,
        ]);
    }

    /**
     * Test that the grade_level field is stored as an integer.
     * Ensures that the grade_level is an integer.
     *
     * @return void
     */
    public function test_grade_level_field_is_integer()
    {
        // Create a student with a specific grade level
        $student = Student::factory()->create([
            'grade_level' => 7,
        ]);

        // Assert that the grade_level is an integer
        $this->assertIsInt($student->grade_level);
    }
}
