<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'description',
        'images',
        'star_rating',
        'contact_email',
        'contact_phone',
        'is_active',
        'is_featured',
    ];

    protected $casts = [
        'images' => 'array', 
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    // Relationships
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    // Add local scope for featured hotels 
    public function scopeFeatured(Builder $query): Builder 
    {
        return $query->where('is_featured', true);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

     public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'amenity_hotel'); 
    }


}
