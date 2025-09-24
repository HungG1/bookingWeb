<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // SỬA DÒNG NÀY: Thêm vào sau 'customer_notes'
            $table->time('arrival_time')->nullable()->after('customer_notes');

            // Giữ nguyên phần thêm tax và fee
            // Đảm bảo 'discount_amount' tồn tại từ migration gốc
            $table->decimal('tax_amount', 15, 2)->default(0)->after('discount_amount');
            $table->decimal('service_fee_amount', 15, 2)->default(0)->after('tax_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Giữ nguyên rollback
            $table->dropColumn(['arrival_time', 'tax_amount', 'service_fee_amount']);
        });
    }
};