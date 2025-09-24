<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade'); // Khóa ngoại tới hotels
            $table->string('room_type_name'); // Tên loại phòng (vd: Standard, Deluxe)
            $table->text('description')->nullable();
            $table->decimal('base_price', 10, 2); // Giá cơ bản
            $table->unsignedInteger('number_of_rooms'); // Tổng số phòng vật lý loại này
            $table->unsignedTinyInteger('max_occupancy')->default(1); // Số người tối đa
            $table->json('images')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
