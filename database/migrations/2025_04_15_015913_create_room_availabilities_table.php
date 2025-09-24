<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomAvailabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('room_availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->unsignedInteger('available_count'); // Số lượng phòng còn trống vào ngày này
            $table->decimal('price', 10, 2)->nullable(); // Giá ghi đè cho ngày này (nếu có)
            $table->unique(['room_id', 'date']); // Mỗi phòng chỉ có 1 record/ngày
            $table->timestamps(); // Có thể không cần thiết nếu bạn cập nhật thủ công
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_availabilities');
    }
};
