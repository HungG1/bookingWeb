<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceRulesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('price_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('days_of_week')->nullable(); // Lưu dạng JSON ["Mon", "Tue"] hoặc string "1,2,3"
            $table->enum('price_modifier_type', ['fixed_amount', 'percentage']); // Giá cố định hoặc %
            $table->decimal('price_modifier_value', 10, 2); // Giá trị thay đổi
            $table->integer('priority')->default(0); // Độ ưu tiên nếu rule chồng chéo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_rules');
    }
};
