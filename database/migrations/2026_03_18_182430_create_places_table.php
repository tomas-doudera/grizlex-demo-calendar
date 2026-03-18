<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('places', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('short_title')->nullable();
            $table->text('description')->nullable();
            $table->string('type')->nullable();
            $table->unsignedInteger('capacity')->default(1);
            $table->string('color')->nullable();
            $table->string('image_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->unsignedInteger('min_booking_minutes')->default(30);
            $table->unsignedInteger('max_booking_minutes')->default(120);
            $table->unsignedInteger('booking_interval_minutes')->default(15);
            $table->unsignedInteger('advance_booking_days')->default(30);
            $table->unsignedInteger('cancellation_hours')->default(24);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('places');
    }
};
