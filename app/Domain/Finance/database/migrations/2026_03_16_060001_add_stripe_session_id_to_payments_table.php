<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('payments', 'stripe_session_id')) {
            return;
        }

        Schema::table('payments', function (Blueprint $table) {
            $table->string('stripe_session_id')->nullable()->unique()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('stripe_session_id');
        });
    }
};
