<?php

namespace App\Observers;

use App\Models\Tutor;
use App\Models\TutorRateChange;

class TutorObserver
{
    /**
     * Handle the Tutor "updating" event.
     *
     * @return void
     */
    public function updating(Tutor $tutor)
    {
        if ($tutor->isDirty('hourly_rate')) {
            TutorRateChange::create([
                'tutor_id' => $tutor->id,
                'old_rate' => $tutor->getOriginal('hourly_rate'),
                'new_rate' => $tutor->hourly_rate,
                'changed_at' => now(),
            ]);
        }
    }
}
