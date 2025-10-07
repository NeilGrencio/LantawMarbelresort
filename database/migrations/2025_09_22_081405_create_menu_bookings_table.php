<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('menu_bookings')) {
            Schema::create('menu_bookings', function (Blueprint $table) {
                $table->id(); // Primary key
                $table->unsignedBigInteger('menu_id');   // FK to menu table
                $table->unsignedBigInteger('booking_id'); // FK to booking table
                $table->integer('quantity')->default(1);
                $table->decimal('price', 10, 2)->default(0); // price snapshot
                $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_bookings');
    }
};
