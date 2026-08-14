<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('reviewer_name');
            $table->tinyInteger('rating');
            $table->text('comment')->nullable();
            $table->text('seller_response')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['business_id', 'is_active']);
            $table->unique('order_id');
            $table->unique('booking_id');
            $table->index('user_id');
        });

        Schema::table('businesses', function (Blueprint $table) {
            $table->decimal('average_rating', 3, 2)->default(0)->after('is_active');
            $table->integer('review_count')->default(0)->after('average_rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');

        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn(['average_rating', 'review_count']);
        });
    }
};
