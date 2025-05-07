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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['apartment', 'house', 'villa', 'room']);
            $table->decimal('price_per_night', 10, 2);
            $table->integer('capacity');
            $table->integer('bedrooms');
            $table->integer('bathrooms');
            $table->string('address');
            $table->string('city');
            $table->string('country');
            $table->boolean('is_available')->default(true);
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
