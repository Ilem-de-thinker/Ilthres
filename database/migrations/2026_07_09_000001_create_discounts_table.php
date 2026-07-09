<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->string('code');
            $table->string('type'); // percentage, fixed_amount
            $table->decimal('value', 12, 2);
            $table->decimal('min_order', 12, 2)->default(0);
            $table->unsignedInteger('max_uses')->nullable();
            $table->unsignedInteger('used_count')->default(0);
            $table->date('expires_at')->nullable();
            $table->string('scope')->default('all'); // all, specific
            $table->json('scope_ids')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['business_id', 'code']);
        });

        Schema::create('discount_redemptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discount_id')->constrained()->cascadeOnDelete();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable();
            $table->decimal('discount_amount', 12, 2);
            $table->string('customer_identifier')->nullable();
            $table->timestamps();
        });

        Schema::create('promo_banners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->string('message');
            $table->string('position')->default('top'); // top, bottom, hero
            $table->string('style')->default('announcement'); // announcement, sale, seasonal
            $table->string('cta_text')->nullable();
            $table->string('cta_url')->nullable();
            $table->date('starts_at')->nullable();
            $table->date('ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promo_banners');
        Schema::dropIfExists('discount_redemptions');
        Schema::dropIfExists('discounts');
    }
};
