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
    Schema::create('deliveries', function (Blueprint $table) {
        $table->id();

        $table->foreignId('request_id')
            ->constrained('food_requests')
            ->cascadeOnDelete();

        $table->foreignId('volunteer_id')
            ->nullable()
            ->constrained('volunteers')
            ->nullOnDelete();

        $table->timestamp('pickup_time')->nullable();
        $table->timestamp('delivered_at')->nullable();
        $table->string('delivery_status')->default('pending');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
