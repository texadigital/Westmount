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
        Schema::create('validation_settings', function (Blueprint $table) {
            $table->id();
            $table->string('field_name');
            $table->string('field_type'); // text, email, phone, date, number, etc.
            $table->json('rules'); // Laravel validation rules as JSON
            $table->json('custom_messages')->nullable(); // Custom error messages
            $table->boolean('is_required')->default(true);
            $table->integer('min_length')->nullable();
            $table->integer('max_length')->nullable();
            $table->string('pattern')->nullable(); // Regex pattern
            $table->text('help_text')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('validation_settings');
    }
};
