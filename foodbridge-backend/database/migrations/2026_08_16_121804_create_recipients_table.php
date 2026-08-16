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
    Schema::create('recipients', function (Blueprint $table) {
        $table->id();

        $table->foreignId('ngo_id')
            ->constrained('ngos')
            ->cascadeOnDelete();

        $table->string('full_name');
        $table->string('phone')->nullable();
        $table->text('address')->nullable();
        $table->unsignedInteger('household_size')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipients');
    }
};
