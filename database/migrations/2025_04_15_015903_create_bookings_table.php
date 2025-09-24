<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Cho phép khách vãng lai
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->unsignedTinyInteger('num_adults')->default(1);
            $table->unsignedTinyInteger('num_children')->default(0);
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            $table->text('customer_notes')->nullable();

            $table->decimal('base_price', 10, 2); // Giá gốc phòng
            $table->decimal('discount_amount', 10, 2)->default(0); // Số tiền giảm giá
            $table->decimal('final_price', 10, 2); // Giá cuối cùng = base - discount

            $table->enum('status', [
                'pending', 'confirmed', 'cancelled_by_user',
                'cancelled_by_admin', 'checked_in', 'checked_out', 'no_show'
            ])->default('pending');

            $table->enum('payment_status', [
                'unpaid', 'paid', 'partially_paid', 'refunded', 'payment_failed'
            ])->default('unpaid');

            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable()->index(); // Mã giao dịch từ cổng thanh toán
            $table->timestamps();

            $table->index(['check_in_date', 'check_out_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
