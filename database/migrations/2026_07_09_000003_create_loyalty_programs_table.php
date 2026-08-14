<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loyalty_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->string('name')->default('Loyalty Program');
            $table->json('earning_rules')->nullable();
            $table->json('rewards')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('loyalty_points_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('customer_identifier')->nullable();
            $table->string('action'); // earned, redeemed
            $table->string('reason'); // visit, review, referral, purchase, appointment, share
            $table->unsignedInteger('points');
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->timestamps();
            $table->index(['business_id', 'customer_identifier'], 'loyalty_pts_biz_cust_idx');
        });

        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('referrer_id')->constrained('users')->cascadeOnDelete();
            $table->string('code');
            $table->string('status')->default('active'); // active, converted, expired
            $table->unsignedBigInteger('referred_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('converted_at')->nullable();
            $table->timestamps();
            $table->unique(['business_id', 'code']);
        });

        Schema::create('referral_rewards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referral_id')->constrained()->cascadeOnDelete();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // points, discount
            $table->decimal('value', 12, 2);
            $table->string('status')->default('pending'); // pending, awarded, redeemed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referral_rewards');
        Schema::dropIfExists('referrals');
        Schema::dropIfExists('loyalty_points_transactions');
        Schema::dropIfExists('loyalty_programs');
    }
};
