<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->text('description')->nullable()->after('title');
            $table->string('email')->nullable()->after('description');
            $table->string('phone')->nullable()->after('email');
            $table->string('website')->nullable()->after('phone');
            $table->text('address')->nullable()->after('website');
            $table->string('city')->nullable()->after('address');
            $table->string('country')->nullable()->after('city');
            $table->string('logo_url')->nullable()->after('country');
            $table->boolean('is_active')->default(true)->after('logo_url');
            $table->time('opening_time')->nullable()->after('is_active');
            $table->time('closing_time')->nullable()->after('opening_time');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'description', 'email', 'phone', 'website', 'address',
                'city', 'country', 'logo_url', 'is_active', 'opening_time', 'closing_time',
            ]);
        });
    }
};
