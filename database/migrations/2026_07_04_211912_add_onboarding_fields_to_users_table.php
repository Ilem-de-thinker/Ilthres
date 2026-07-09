<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds onboarding progress tracking to the users table.
     * onboarding_step: 0 = not started, 1-4 = current wizard step, 5 = completed.
     * onboarding_completed: shortcut flag for middleware checks.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('onboarding_step')->default(0)->after('remember_token');
            $table->boolean('onboarding_completed')->default(false)->after('onboarding_step');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['onboarding_step', 'onboarding_completed']);
        });
    }
};
