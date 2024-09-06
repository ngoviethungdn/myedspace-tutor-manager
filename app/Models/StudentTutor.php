<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class StudentTutor
 *
 * Represents the pivot table for the many-to-many relationship
 * between students and tutors.
 */
class StudentTutor extends Model
{
    use HasFactory;

    /**
     * The name of the table associated with the model.
     *
     * @var string
     */
    protected $table = 'student_tutor';
}
