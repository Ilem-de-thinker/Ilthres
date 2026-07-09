<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->boolean('is_discoverable')->default(false)->after('is_active');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_public')->default(false)->after('is_featured');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->boolean('is_public')->default(false)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn('is_discoverable');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_public');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('is_public');
        });
    }
};
