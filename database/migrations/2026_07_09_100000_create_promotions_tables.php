<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('promotion_type'); // percentage, fixed_amount, fixed_price, buy_x_get_y, free_shipping, free_item
            $table->string('scope'); // all, category, selected_products, selected_services
            $table->string('discount_type')->nullable(); // percentage, fixed
            $table->decimal('discount_value', 12, 2)->nullable();
            $table->decimal('fixed_price', 12, 2)->nullable();
            $table->integer('buy_x')->nullable();
            $table->integer('buy_y')->nullable(); // get Y free or Y% off
            $table->string('buy_y_discount_type')->nullable(); // percentage, fixed, free
            $table->decimal('buy_y_discount_value', 12, 2)->nullable();
            $table->decimal('min_order_amount', 12, 2)->nullable(); // for free shipping
            $table->integer('max_uses')->nullable();
            $table->integer('uses_count')->default(0);
            $table->integer('customer_limit')->nullable();
            $table->integer('priority')->default(0);
            $table->string('status'); // draft, scheduled, active, paused, expired, archived
            $table->string('banner_image')->nullable();
            $table->boolean('show_countdown')->default(true);
            $table->boolean('show_badge')->default(true);
            $table->boolean('featured')->default(false);
            $table->json('badge_config')->nullable(); // color, position, custom_text
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['business_id', 'slug']);
            $table->index(['business_id', 'status']);
            $table->index(['business_id', 'starts_at', 'ends_at']);
        });

        Schema::create('promotionables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_id')->constrained()->cascadeOnDelete();
            $table->morphs('promotionable');
            $table->timestamps();

            $table->unique(['promotion_id', 'promotionable_id', 'promotionable_type'], 'promotionable_unique');
        });

        Schema::create('promotion_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['promotion_id', 'category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotion_categories');
        Schema::dropIfExists('promotionables');
        Schema::dropIfExists('promotions');
    }
};
