<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('places', function (Blueprint $table) {
            $table->text('description')->nullable()->after('short_title');
            $table->string('type')->nullable()->after('description');
            $table->integer('capacity')->default(1)->after('type');
            $table->decimal('hourly_rate', 10, 2)->nullable()->after('capacity');
            $table->string('color')->nullable()->after('hourly_rate');
            $table->boolean('is_active')->default(true)->after('color');
            $table->json('amenities')->nullable()->after('is_active');
            $table->integer('sort_order')->default(0)->after('amenities');
        });
    }

    public function down(): void
    {
        Schema::table('places', function (Blueprint $table) {
            $table->dropColumn([
                'description', 'type', 'capacity', 'hourly_rate',
                'color', 'is_active', 'amenities', 'sort_order',
            ]);
        });
    }
};
