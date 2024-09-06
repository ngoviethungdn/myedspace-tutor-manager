<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Student
 *
 * Represents a student entity in the application.
 * A student can be associated with multiple tutors.
 */
class Student extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',        // Student's full name
        'email',       // Student's email address
        'grade_level', // Grade level of the student (e.g., 1st grade, 12th grade, etc.)
    ];

    /**
     * Define the many-to-many relationship between students and tutors.
     *
     * A student can have multiple tutors, and a tutor can have multiple students.
     */
    public function tutors(): BelongsToMany
    {
        // Define the pivot table 'student_tutor' for the many-to-many relationship
        return $this->belongsToMany(Tutor::class, 'student_tutor');
    }
}
