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
    Schema::create('food_donations', function (Blueprint $table) {
        $table->id();

        $table->foreignId('donor_id')
            ->constrained('donors')
            ->cascadeOnDelete();

        $table->string('food_name');
        $table->string('food_category');
        $table->decimal('quantity', 10, 2);
        $table->string('unit');

        $table->dateTime('prepared_at')->nullable();
        $table->dateTime('expiry_at');

        $table->string('availability_status')->default('available');
        $table->string('donation_status')->default('active');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_donations');
    }
};
