<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('themes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('thumbnail')->nullable();
            $table->json('config')->nullable();
            $table->boolean('is_premium')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('business_theme', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('theme_id')->constrained();
            $table->json('customization')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            $table->unique(['business_id', 'theme_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_theme');
        Schema::dropIfExists('themes');
    }
};
