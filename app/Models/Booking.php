<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'hotel_id',
        'room_id',
        'check_in_date',
        'check_out_date',
        'num_adults',          // Từ migration gốc
        'num_children',        // Từ migration gốc
        'customer_name',       // Từ migration gốc
        'customer_email',      // Từ migration gốc
        'customer_phone',      // Từ migration gốc
        'customer_notes',      // Từ migration gốc (Dùng thay cho special_requests)
        'base_price',          // Từ migration gốc (Có thể map từ baseAmount)
        'discount_amount',     // Từ migration gốc + component
        'tax_amount',          // Từ migration mới
        'service_fee_amount',  // Từ migration mới
        'final_price',         // Từ migration gốc (Có thể map từ totalAmount)
        'arrival_time',        // Từ migration mới
        'status',              // Từ migration gốc
        'payment_status',      // Từ migration gốc
        'payment_method',      // Từ migration gốc + component
        'transaction_id',      // Từ migration gốc
        // 'discount_code_id', // Giữ lại nếu bạn đã có migration thêm cột này
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'base_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'service_fee_amount' => 'decimal:2',
        'final_price' => 'decimal:2',
         // Cast arrival_time thành H:i khi lấy ra, DB lưu dạng time
        'arrival_time' => 'datetime:H:i',
        // Thêm cast cho các trường số nếu cần
         'num_adults' => 'integer',
         'num_children' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function paymentHistories()
    {
        return $this->hasMany(PaymentHistory::class);
    }

    public function review(): HasOne 
    {
         return $this->hasOne(Review::class);
    }
 
    public function discountCode(): BelongsTo
     {
          // Giả sử bạn có cột discount_code_id và model DiscountCode
          // return $this->belongsTo(DiscountCode::class);
          // Hoặc model Discount
          return $this->belongsTo(Discount::class, 'discount_code_id');
     }
}
