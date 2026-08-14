<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('business_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->string('country', 100);
            $table->string('state', 100);
            $table->string('city', 100);
            $table->text('address');
            $table->string('postal_code', 20)->nullable();
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('phone', 20)->nullable();
            $table->string('email', 255)->nullable();
            $table->json('opening_hours')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->boolean('is_headquarters')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_locations');
    }
};
