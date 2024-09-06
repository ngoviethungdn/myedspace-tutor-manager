<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 *
 * Represents a user of the application, extending the default
 * Laravel authentication user model and implementing FilamentUser
 * for Filament panel access.
 */
class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * These are the fields that can be filled via mass assignment.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',       // User's full name
        'email',      // User's email address
        'password',   // User's hashed password
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * These fields will be hidden when the model is converted to an array or JSON.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',        // Hide the password field
        'remember_token',  // Hide the remember token field
    ];

    /**
     * Get the attributes that should be cast.
     *
     * Defines the data types for attributes. For example, 'email_verified_at' will be cast to a Carbon instance.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime', // Cast 'email_verified_at' to a DateTime instance
            'password' => 'hashed',           // Cast 'password' to a hashed value
        ];
    }

    /**
     * Determine if the user can access the Filament panel.
     *
     * This method is used to check if the user has the necessary permissions to access the Filament admin panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // TODO: Implement user permission check logic
        return true;
    }
}
