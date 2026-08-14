<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('promo_banners', function (Blueprint $table) {
            $table->string('image')->nullable()->after('message');
            $table->unsignedInteger('sort_order')->default(0)->after('style');
        });

        Schema::create('bannerables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promo_banner_id')->constrained()->cascadeOnDelete();
            $table->morphs('bannerable');
            $table->timestamps();

            $table->unique(['promo_banner_id', 'bannerable_id', 'bannerable_type'], 'bannerable_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bannerables');

        Schema::table('promo_banners', function (Blueprint $table) {
            $table->dropColumn(['image', 'sort_order']);
        });
    }
};
