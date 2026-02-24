<?php

use App\Models\Booking;
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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete("set null");
            $table->foreignId('vehicle_id')->nullable()->constrained()->onDelete("set null");
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->enum('status', Booking::Statuses());
            $table->enum('payment_status', Booking::PaymentStatuses());
            $table->enum('payment_method', Booking::PaymentMethods());
            $table->decimal('total_amount', 8, 2);
            $table->boolean('deposit_received')->nullable()->default(false);
            $table->timestamps();
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
