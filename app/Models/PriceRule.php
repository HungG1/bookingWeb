<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'start_date',
        'end_date',
        'days_of_week',
        'price_modifier_type',
        'price_modifier_value',
        'priority',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'days_of_week' => 'array', 
        'price_modifier_value' => 'decimal:2',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
