<?php

namespace Tests\Feature\Livewire;

use App\Livewire\TutorSearch;
use App\Models\Tutor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Feature tests for the TutorSearch Livewire component.
 */
class TutorSearchTest extends TestCase
{
    // Use RefreshDatabase to reset the database state after each test
    use RefreshDatabase;

    /**
     * Test the initial state of the component.
     * Ensures that default filter values are set and tutor data is visible.
     */
    public function test_renders_initial_state_correctly()
    {
        // Create 10 tutor records
        $tutors = Tutor::factory(10)->create();

        // Test the initial state of the TutorSearch Livewire component
        Livewire::test(TutorSearch::class)
            ->assertSet('searchSubjects', [])  // Check that the subjects filter is empty
            ->assertSet('minHourlyRate', 0)    // Default minimum hourly rate is 0
            ->assertSet('maxHourlyRate', 100)  // Default maximum hourly rate is 100
            ->assertSet('searchTerm', '')      // Check that the search term is empty
            ->assertSee($tutors->first()->name)  // Ensure the first tutor's name is visible
            ->assertDontSee('No tutors found');  // Ensure the 'No tutors found' message is not shown
    }

    /**
     * Test that tutors can be filtered by subjects.
     * Ensures only tutors with the selected subject are displayed.
     */
    public function test_can_filter_tutors_by_subjects()
    {
        // Create tutors with different subjects
        $tutorWithMath = Tutor::factory()->create(['subjects' => ['Math']]);
        $tutorWithScience = Tutor::factory()->create(['subjects' => ['Science']]);

        // Test filtering by 'Math' subject
        Livewire::test(TutorSearch::class)
            ->set('searchSubjects', ['Math'])  // Set filter to 'Math'
            ->assertSee($tutorWithMath->name)  // Ensure tutor with 'Math' is shown
            ->assertDontSee($tutorWithScience->name);  // Ensure tutor with 'Science' is not shown
    }

    /**
     * Test that tutors can be filtered by hourly rate.
     * Ensures that tutors within the rate range are displayed.
     */
    public function test_can_filter_tutors_by_hourly_rate()
    {
        // Create tutors with different hourly rates
        $tutorInRange = Tutor::factory()->create(['hourly_rate' => 50]);
        $tutorOutOfRange = Tutor::factory()->create(['hourly_rate' => 150]);

        // Test filtering by hourly rate range (20 to 100)
        Livewire::test(TutorSearch::class)
            ->set('minHourlyRate', 20)  // Set minimum hourly rate
            ->set('maxHourlyRate', 100)  // Set maximum hourly rate
            ->assertSee($tutorInRange->name)  // Ensure tutor within range is shown
            ->assertDontSee($tutorOutOfRange->name);  // Ensure tutor outside range is not shown
    }

    /**
     * Test that tutors can be filtered by search term.
     * Ensures that only tutors whose names match the search term are displayed.
     */
    public function test_can_filter_tutors_by_search_term()
    {
        // Create tutors with different names
        Tutor::factory()->create(['name' => 'John Doe']);
        Tutor::factory()->create(['name' => 'Jane Smith']);

        // Test filtering by the search term 'John'
        Livewire::test(TutorSearch::class)
            ->set('searchTerm', 'John')  // Set search term to 'John'
            ->assertSee('John Doe')      // Ensure 'John Doe' is shown
            ->assertDontSee('Jane Smith');  // Ensure 'Jane Smith' is not shown
    }

    /**
     * Test validation rules on the component.
     * Ensures that invalid inputs are caught and proper error messages are displayed.
     */
    public function test_enforces_validation_rules()
    {
        // Test invalid minimum hourly rate
        Livewire::test(TutorSearch::class)
            ->set('minHourlyRate', -10)  // Set an invalid negative hourly rate
            ->assertHasErrors(['minHourlyRate' => 'min']);  // Assert validation error on 'minHourlyRate'

        // Test invalid maximum hourly rate
        Livewire::test(TutorSearch::class)
            ->set('maxHourlyRate', -5)  // Set an invalid negative maximum rate
            ->assertHasErrors(['maxHourlyRate' => 'min']);  // Assert validation error on 'maxHourlyRate'
    }

    /**
     * Test applying multiple filters simultaneously.
     * Ensures that combined filters work as expected.
     */
    public function test_applies_combined_filters_correctly()
    {
        // Create tutors with different subjects, hourly rates, and names
        Tutor::factory()->create([
            'name' => 'John Doe',
            'subjects' => ['Math'],
            'hourly_rate' => 50,
        ]);

        Tutor::factory()->create([
            'name' => 'Jane Smith',
            'subjects' => ['Science'],
            'hourly_rate' => 150,
        ]);

        // Test combined filtering by subject, hourly rate range, and search term
        Livewire::test(TutorSearch::class)
            ->set('searchSubjects', ['Math'])  // Set subject filter to 'Math'
            ->set('minHourlyRate', 40)         // Set minimum hourly rate to 40
            ->set('maxHourlyRate', 100)        // Set maximum hourly rate to 100
            ->set('searchTerm', 'John')        // Set search term to 'John'
            ->assertSee('John Doe')            // Ensure 'John Doe' is shown
            ->assertDontSee('Jane Smith');     // Ensure 'Jane Smith' is not shown
    }
}
