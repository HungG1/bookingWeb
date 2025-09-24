<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable ; 

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'address',
        'avatar'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    // Relationships
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Determine if the user can access the Filament panel (e.g., /admin).
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // If an 'is_admin' attribute/column exists and is truthy, allow access.
        $isAdminAttr = $this->getAttribute('is_admin');
        if (!is_null($isAdminAttr)) {
            return (bool) $isAdminAttr;
        }

        // Otherwise, allow access if the user's email is listed in FILAMENT_ADMIN_EMAILS
        // Example: FILAMENT_ADMIN_EMAILS="admin@example.com,owner@example.com"
        $emails = array_filter(array_map('trim', explode(',', (string) env('FILAMENT_ADMIN_EMAILS'))));
        return in_array($this->email, $emails, true);
    }



}
