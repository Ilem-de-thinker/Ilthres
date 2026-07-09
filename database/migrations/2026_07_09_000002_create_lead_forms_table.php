<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lead_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('template')->nullable(); // contact_us, get_a_quote, book_a_call, custom
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('submission_count')->default(0);
            $table->timestamps();
            $table->unique(['business_id', 'slug']);
        });

        Schema::create('lead_form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_form_id')->constrained()->cascadeOnDelete();
            $table->string('label');
            $table->string('type'); // text, textarea, email, phone, select, checkbox, radio, file, date
            $table->boolean('required')->default(false);
            $table->json('options')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
        });

        Schema::create('lead_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_form_id')->constrained()->cascadeOnDelete();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->json('data');
            $table->string('status')->default('new'); // new, contacted, qualified, converted, lost
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['business_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_submissions');
        Schema::dropIfExists('lead_form_fields');
        Schema::dropIfExists('lead_forms');
    }
};
