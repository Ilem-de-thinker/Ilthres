<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->json('tags')->nullable()->after('seo');
            $table->integer('search_boost')->default(0)->after('tags');
            $table->timestamp('last_indexed_at')->nullable()->after('search_boost');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->json('tags')->nullable()->after('sort_order');
            $table->timestamp('last_indexed_at')->nullable()->after('tags');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->json('tags')->nullable()->after('sort_order');
            $table->timestamp('last_indexed_at')->nullable()->after('tags');
        });
    }

    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn(['tags', 'search_boost', 'last_indexed_at']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['tags', 'last_indexed_at']);
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['tags', 'last_indexed_at']);
        });
    }
};
