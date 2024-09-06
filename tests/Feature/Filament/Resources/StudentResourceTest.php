<?php

namespace Tests\Feature\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages\CreateStudent;
use App\Filament\Resources\StudentResource\Pages\EditStudent;
use App\Filament\Resources\StudentResource\Pages\ListStudents;
use App\Models\Student;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Feature tests for the Student Filament Resource.
 */
class StudentResourceTest extends TestCase
{
    // Use RefreshDatabase to ensure the database is reset after each test
    use RefreshDatabase;

    /**
     * @var User
     *           Admin user used to authenticate the requests.
     */
    protected User $admin;

    /**
     * Set up the test environment.
     * This method runs before each test to initialize the admin user.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Create and authenticate an admin user
        $this->admin = User::factory()->create();
        $this->actingAs($this->admin);
    }

    /**
     * Test that the list students page renders correctly.
     * It verifies that all the students are displayed in the table.
     */
    public function test_list_students_page_renders_correctly()
    {
        // Create 3 sample students
        Student::factory()->count(3)->create();

        // Test that the ListStudents Livewire component renders the student data correctly
        Livewire::test(ListStudents::class)
            ->assertCanSeeTableRecords(Student::all())  // Verify that all student records are visible
            ->assertCountTableRecords(Student::all()->count());  // Check that the number of displayed records matches the database
    }

    /**
     * Test creating a new student.
     * It fills out the form with student data and verifies that the student is created in the database.
     */
    public function test_create_student()
    {
        // Sample student data for form submission
        $studentData = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'grade_level' => 10,
        ];

        // Test the CreateStudent Livewire component
        Livewire::test(CreateStudent::class)
            ->fillForm($studentData)  // Fill the form with student data
            ->call('create')  // Call the 'create' action to submit the form
            ->assertHasNoFormErrors();  // Ensure no form validation errors occur

        // Assert that the student is stored in the database
        $this->assertDatabaseHas('students', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'grade_level' => 10,
        ]);
    }

    /**
     * Test editing an existing student.
     * It changes the student's information and verifies that the updates are applied in the database.
     */
    public function test_edit_student()
    {
        // Create a student to be edited
        $student = Student::factory()->create();

        // Test the EditStudent Livewire component
        Livewire::test(EditStudent::class, ['record' => $student->getKey()])
            ->fillForm([
                'name' => 'Jane Doe',
                'email' => 'janedoe@example.com',
                'grade_level' => 11,
            ])  // Fill the form with updated student data
            ->call('save')  // Call the 'save' action to apply the changes
            ->assertHasNoFormErrors();  // Ensure no validation errors occur

        // Assert that the student's data is updated in the database
        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'name' => 'Jane Doe',
            'email' => 'janedoe@example.com',
            'grade_level' => 11,
        ]);
    }

    /**
     * Test deleting a student.
     * It ensures that the student record is removed from the database after deletion.
     */
    public function test_delete_student()
    {
        // Create a student to be deleted
        $student = Student::factory()->create();

        // Test the ListStudents Livewire component to delete the student using the DeleteAction
        Livewire::test(ListStudents::class)
            ->callTableAction(DeleteAction::class, $student);  // Trigger the DeleteAction to remove the student

        // Assert that the student is no longer in the database
        $this->assertDatabaseMissing('students', [
            'id' => $student->id,
        ]);
    }
}
