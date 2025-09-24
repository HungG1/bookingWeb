<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder; 



class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'room_type_name',
        'description',
        'base_price',
        'number_of_rooms',
        'max_occupancy',
        'images',
        'is_active',
    ];

    protected $casts = [
        'images' => 'array',
        'is_active' => 'boolean',
        'base_price' => 'decimal:2',
    ];

    // Relationships
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function priceRules()
    {
        return $this->hasMany(PriceRule::class);
    }

    public function roomAvailabilities()
    {
        return $this->hasMany(RoomAvailability::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'amenity_room');
    }

    /**
     * Scope a query to only include rooms available between the given dates.
     * Tìm những phòng KHÔNG có booking nào chồng chéo trong khoảng thời gian này.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $checkInDate  (Y-m-d format)
     * @param  string  $checkOutDate (Y-m-d format)
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailableBetween(Builder $query, string $checkInDate, string $checkOutDate): Builder
    {
        try {
            $checkIn = \Carbon\Carbon::parse($checkInDate);
            $checkOut = \Carbon\Carbon::parse($checkOutDate);
            if ($checkOut->lte($checkIn)) {
                 return $query->whereRaw('1 = 0'); 
            }
        } catch (\Exception $e) {
             return $query->whereRaw('1 = 0'); 
        }

        return $query->whereDoesntHave('bookings', function (Builder $bookingQuery) use ($checkInDate, $checkOutDate) {
            $bookingQuery->where('check_out_date', '>', $checkInDate)
                         ->where('check_in_date', '<', $checkOutDate);
        });      
    }
}
