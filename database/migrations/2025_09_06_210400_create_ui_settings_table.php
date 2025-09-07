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
        Schema::create('ui_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('primary_color')->default('#3B82F6');
            $table->string('secondary_color')->default('#6B7280');
            $table->string('accent_color')->default('#10B981');
            $table->string('background_color')->default('#FFFFFF');
            $table->string('text_color')->default('#1F2937');
            $table->string('logo_url')->nullable();
            $table->string('favicon_url')->nullable();
            $table->string('site_title')->default('Association Westmount');
            $table->text('site_description')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('footer_text')->nullable();
            $table->json('custom_css')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ui_settings');
    }
};
