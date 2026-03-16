<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('type')->default('individual')->after('staff_id');
            $table->nullableMorphs('reservable');
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropMorphs('reservable');
            $table->dropColumn(['type', 'checked_in_at', 'cancelled_at', 'cancellation_reason']);
        });
    }
};
