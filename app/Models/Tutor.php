<?php

namespace App\Models;

use App\Observers\TutorObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Tutor
 *
 * Represents a tutor entity in the application.
 * A tutor can be associated with multiple students and have multiple rate changes.
 */
class Tutor extends Model
{
    use HasFactory;

    // TODO: Retrieve subjects from the database dynamically if needed
    /**
     * Predefined list of subjects that a tutor can teach.
     *
     * @var array<string>
     */
    const SUBJECTS = [
        'Mathematics',
        'Physics',
        'Chemistry',
        'Biology',
        'English Literature',
        'History',
        'Geography',
        'Computer Science',
        'Economics',
        'Statistics',
        'French',
        'Spanish',
        'Music Theory',
        'Art',
        'Philosophy',
        'Political Science',
        'Environmental Science',
        'Psychology',
        'Sociology',
        'Algebra',
        'Calculus',
        'Geometry',
        'Trigonometry',
        'Creative Writing',
        'Public Speaking',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'avatar',        // URL or path to the tutor's avatar image
        'name',          // Tutor's full name
        'email',         // Tutor's email address
        'hourly_rate',   // Hourly rate for tutoring services
        'bio',           // Brief biography of the tutor
        'subjects',      // Array of subjects the tutor can teach
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'subjects' => 'array', // Cast the 'subjects' attribute to an array
    ];

    /**
     * Boot method for the model.
     *
     * Registers the TutorObserver to handle model events.
     */
    protected static function boot()
    {
        parent::boot();

        static::observe(TutorObserver::class);
    }

    /**
     * Define the many-to-many relationship between tutors and students.
     *
     * A tutor can have multiple students, and a student can have multiple tutors.
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_tutor');
    }

    /**
     * Get all rate changes associated with the tutor.
     *
     * A tutor can have multiple rate changes over time.
     */
    public function rateChanges(): HasMany
    {
        return $this->hasMany(TutorRateChange::class);
    }

    /**
     * Get the highest-paid subject based on the average hourly rate.
     *
     * This method performs a query to find the subject with the highest average hourly rate.
     */
    public static function getHighestPaidSubject(): ?string
    {
        $highestPaidSubject = self::selectRaw('json_unquote(json_extract(subjects, "$[0]")) as subject')
            ->groupBy('subject')
            ->orderByRaw('avg(hourly_rate) desc')
            ->first();

        return $highestPaidSubject ? $highestPaidSubject->subject : null;
    }

    /**
     * Perform a search query on the Tutor model.
     *
     * This method allows filtering tutors based on various search parameters.
     *
     * @param  array<string, mixed>  $parameters
     */
    public static function querySearch(array $parameters = []): Builder
    {
        return Tutor::query()
            ->when($parameters['search'] ?? null, function (Builder $query) use ($parameters) {
                $query->where('name', 'like', "%{$parameters['search']}%");
            })
            ->when($parameters['subjects'] ?? null, function (Builder $query) use ($parameters) {
                $query->whereJsonContains('subjects', $parameters['subjects']);
            })
            ->when(isset($parameters['min_hourly_rate']), function (Builder $query) use ($parameters) {
                $query->where('hourly_rate', '>=', $parameters['min_hourly_rate']);
            })
            ->when(isset($parameters['max_hourly_rate']), function (Builder $query) use ($parameters) {
                $query->where('hourly_rate', '<=', $parameters['max_hourly_rate']);
            });
    }
}
