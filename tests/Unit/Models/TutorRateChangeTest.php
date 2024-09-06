<?php

namespace Tests\Unit\Models;

use App\Models\Tutor;
use App\Models\TutorRateChange;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Unit tests for the TutorRateChange model.
 */
class TutorRateChangeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the TutorRateChange model has the correct fillable attributes.
     *
     * @return void
     */
    public function test_has_fillable_attributes()
    {
        // Create a new instance of the TutorRateChange model
        $rateChange = new TutorRateChange;

        // Assert that the fillable attributes match the expected ones
        $this->assertEquals(
            ['tutor_id', 'old_rate', 'new_rate', 'changed_at'],
            $rateChange->getFillable()
        );
    }

    /**
     * Test that a TutorRateChange belongs to a Tutor.
     *
     * @return void
     */
    public function test_belongs_to_a_tutor()
    {
        // Create a tutor and a corresponding rate change
        $tutor = Tutor::factory()->create();
        $rateChange = TutorRateChange::factory()->create([
            'tutor_id' => $tutor->id,
        ]);

        // Assert that the rate change belongs to the created tutor
        $this->assertInstanceOf(Tutor::class, $rateChange->tutor);
        $this->assertEquals($tutor->id, $rateChange->tutor->id);
    }
}
