<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('services', 'requires_payment')) {
            return;
        }

        Schema::table('services', function (Blueprint $table) {
            $table->boolean('requires_payment')->default(false)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('requires_payment');
        });
    }
};
