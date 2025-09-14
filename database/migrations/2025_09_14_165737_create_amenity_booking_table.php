<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('amenity_booking_table', function (Blueprint $table) {
            $table->id(); // Primary key (auto-increment bigint)

            $table->unsignedBigInteger('amenity_id');
            $table->unsignedBigInteger('booking_id');
            $table->date('date');
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->timestamps();

            // Foreign keys
            $table->foreign('amenity_id')
                  ->references('amenityID')->on('amenities')
                  ->onDelete('cascade');

            $table->foreign('booking_id')
                  ->references('bookingID')->on('booking')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('amenity_booking_table');
    }
};
