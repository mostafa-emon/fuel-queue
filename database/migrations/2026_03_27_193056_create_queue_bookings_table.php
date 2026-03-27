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
        Schema::create('queue_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('fuel_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('vehicle_type_id')->constrained()->onDelete('cascade');
            $table->string('serial_number')->unique();
            $table->string('qr_token')->unique();
            $table->enum('status', ['pending', 'completed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queue_bookings');
    }
};
