<?php

namespace Tests\Feature\Filament\Resources;

use App\Filament\Resources\TutorResource;
use App\Models\Tutor;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Feature tests for the Tutor Filament Resource.
 */
class TutorResourceTest extends TestCase
{
    // Use RefreshDatabase to reset the database state after each test
    use RefreshDatabase;

    /**
     * @var User
     *           Admin user used to authenticate the requests.
     */
    protected User $admin;

    /**
     * Set up the test environment.
     * This method runs before each test and initializes the admin user.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Create and authenticate an admin user
        $this->admin = User::factory()->create();
        $this->actingAs($this->admin);
    }

    /**
     * Test that the list of tutors is displayed correctly.
     * It verifies that the tutor's name and email are shown in the response.
     */
    public function test_can_list_tutors()
    {
        // Create a tutor record
        $tutor = Tutor::factory()->create();

        // Make a GET request to the TutorResource index page
        $response = $this->get(TutorResource::getUrl('index'));

        // Assert that the response status is 200 and the tutor's name and email are present in the output
        $response->assertStatus(200)
            ->assertSee($tutor->name)
            ->assertSee($tutor->email);
    }

    /**
     * Test that the create tutor form is viewable.
     * It verifies that the form contains the 'Create Tutor' label.
     */
    public function test_can_view_create_tutor_form()
    {
        // Make a GET request to the create tutor page
        $response = $this->get(route('filament.admin.resources.tutors.create'));

        // Assert that the response status is 200 and the form displays 'Create Tutor'
        $response->assertStatus(200)
            ->assertSee('Create Tutor');
    }

    /**
     * Test that the edit tutor form is viewable.
     * It verifies that the form contains the 'Edit Tutor' label.
     */
    public function test_can_view_edit_tutor_form()
    {
        // Create a tutor to edit
        $tutor = Tutor::factory()->create();

        // Make a GET request to the edit tutor page for the created tutor
        $response = $this->get(route('filament.admin.resources.tutors.edit', $tutor->id));

        // Assert that the response status is 200 and the form displays 'Edit Tutor'
        $response->assertStatus(200)
            ->assertSee('Edit Tutor');
    }

    /**
     * Test creating a new tutor.
     * It submits the form data and verifies that the tutor is stored in the database.
     */
    public function test_can_create_a_tutor()
    {
        // Generate new tutor data without saving it to the database
        $newData = Tutor::factory()->make();

        // Test the CreateTutor Livewire component and fill the form with the generated data
        Livewire::actingAs($this->admin)
            ->test(\App\Filament\Resources\TutorResource\Pages\CreateTutor::class)
            ->set('data.name', $newData->name)
            ->set('data.email', $newData->email)
            ->set('data.hourly_rate', $newData->hourly_rate)
            ->set('data.subjects', $newData->subjects)
            ->set('data.bio', $newData->bio)
            ->call('create')  // Call the 'create' action to submit the form
            ->assertHasNoFormErrors();  // Ensure no form validation errors occur

        // Assert that the tutor data is stored in the database
        $this->assertDatabaseHas('tutors', [
            'name' => $newData->name,
            'email' => $newData->email,
            'hourly_rate' => $newData->hourly_rate,
            'bio' => $newData->bio,
            'subjects' => json_encode($newData->subjects),
        ]);
    }

    /**
     * Test editing an existing tutor.
     * It changes the tutor's information and verifies that the updates are saved in the database.
     */
    public function test_can_edit_a_tutor()
    {
        // Create a tutor to edit and generate new data for updating the tutor
        $tutor = Tutor::factory()->create();
        $newData = Tutor::factory()->make();

        // Test the EditTutor Livewire component with the tutor's existing record
        Livewire::actingAs($this->admin)
            ->test(\App\Filament\Resources\TutorResource\Pages\EditTutor::class, [
                'record' => $tutor->getRouteKey(),
            ])
            ->set('data.name', $newData->name)  // Update the name
            ->set('data.email', $newData->email)  // Update the email
            ->set('data.hourly_rate', $newData->hourly_rate)  // Update the hourly rate
            ->set('data.bio', $newData->bio)  // Update the bio
            ->set('data.subjects', $newData->subjects)  // Update the subjects
            ->call('save')  // Call the 'save' action to apply the changes
            ->assertHasNoFormErrors();  // Ensure no validation errors occur

        // Refresh the tutor instance to get the latest data
        $tutor->refresh();

        // Assert that the updated data matches the new values
        $this->assertEquals($newData->name, $tutor->name);
        $this->assertEquals($newData->email, $tutor->email);
        $this->assertEquals($newData->hourly_rate, $tutor->hourly_rate);
        $this->assertEquals($newData->bio, $tutor->bio);
        $this->assertEquals($newData->subjects, $tutor->subjects);
    }

    /**
     * Test deleting a tutor.
     * It verifies that the tutor is removed from the database after the deletion.
     */
    public function test_can_delete_a_tutor()
    {
        // Create an admin user and a tutor to delete
        $user = User::factory()->create();
        $tutor = Tutor::factory()->create();

        // Test the EditTutor Livewire component and call the delete action
        Livewire::actingAs($this->admin)
            ->test(\App\Filament\Resources\TutorResource\Pages\EditTutor::class, [
                'record' => $tutor->getRouteKey(),
            ])
            ->callAction(DeleteAction::class)  // Call the delete action to remove the tutor
            ->assertRedirect(TutorResource::getUrl('index'));  // Assert that the user is redirected to the tutor list

        // Assert that the tutor is no longer in the database
        $this->assertDatabaseMissing('tutors', [
            'id' => $tutor->id,
        ]);
    }
}
