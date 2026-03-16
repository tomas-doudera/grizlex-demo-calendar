<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->foreignId('customer_id')->nullable()->after('place_id')->constrained()->nullOnDelete();
            $table->foreignId('service_id')->nullable()->after('customer_id')->constrained()->nullOnDelete();
            $table->foreignId('staff_id')->nullable()->after('service_id')->constrained()->nullOnDelete();
            $table->string('status')->default('pending')->after('booked_count');
            $table->decimal('total_price', 10, 2)->nullable()->after('status');
            $table->text('notes')->nullable()->after('total_price');
            $table->string('guest_name')->nullable()->after('notes');
            $table->string('guest_email')->nullable()->after('guest_name');
            $table->string('guest_phone')->nullable()->after('guest_email');
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropForeign(['service_id']);
            $table->dropForeign(['staff_id']);
            $table->dropColumn([
                'customer_id', 'service_id', 'staff_id', 'status',
                'total_price', 'notes', 'guest_name', 'guest_email', 'guest_phone',
            ]);
        });
    }
};
