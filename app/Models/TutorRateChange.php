<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TutorRateChange
 *
 * Represents a record of a tutor's rate change.
 * Tracks the historical changes in the tutor's hourly rate.
 */
class TutorRateChange extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'tutor_id',   // ID of the tutor whose rate has changed
        'old_rate',   // Previous hourly rate before the change
        'new_rate',   // New hourly rate after the change
        'changed_at', // Timestamp when the rate change occurred
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * Set to true if you are using created_at and updated_at timestamps.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Get the tutor associated with the rate change.
     *
     * Defines a relationship where each rate change record belongs to a specific tutor.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }
}
