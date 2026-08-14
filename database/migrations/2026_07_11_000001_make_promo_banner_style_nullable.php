<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promo_banners_tmp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->string('message');
            $table->string('position')->default('top');
            $table->string('style')->nullable()->default('announcement');
            $table->string('cta_text')->nullable();
            $table->string('cta_url')->nullable();
            $table->date('starts_at')->nullable();
            $table->date('ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->string('image')->nullable();
            $table->integer('sort_order')->default(0);
        });

        DB::statement('INSERT INTO promo_banners_tmp (id, business_id, message, position, style, cta_text, cta_url, starts_at, ends_at, is_active, created_at, updated_at, image, sort_order) SELECT id, business_id, message, position, style, cta_text, cta_url, starts_at, ends_at, is_active, created_at, updated_at, image, sort_order FROM promo_banners');

        if (DB::connection()->getDriverName() !== 'sqlite') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        }
        Schema::drop('promo_banners');
        if (DB::connection()->getDriverName() !== 'sqlite') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
        Schema::rename('promo_banners_tmp', 'promo_banners');
    }

    public function down(): void
    {
        Schema::create('promo_banners_tmp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->string('message');
            $table->string('position')->default('top');
            $table->string('style')->default('announcement');
            $table->string('cta_text')->nullable();
            $table->string('cta_url')->nullable();
            $table->date('starts_at')->nullable();
            $table->date('ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->string('image')->nullable();
            $table->integer('sort_order')->default(0);
        });

        DB::statement('INSERT INTO promo_banners_tmp (id, business_id, message, position, style, cta_text, cta_url, starts_at, ends_at, is_active, created_at, updated_at, image, sort_order) SELECT id, business_id, message, position, style, cta_text, cta_url, starts_at, ends_at, is_active, created_at, updated_at, image, sort_order FROM promo_banners');

        if (DB::connection()->getDriverName() !== 'sqlite') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        }
        Schema::drop('promo_banners');
        if (DB::connection()->getDriverName() !== 'sqlite') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
        Schema::rename('promo_banners_tmp', 'promo_banners');
    }
};
