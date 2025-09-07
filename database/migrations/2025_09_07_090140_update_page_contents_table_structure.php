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
        // Check if the columns exist before trying to drop them
        if (Schema::hasColumn('page_contents', 'section')) {
            Schema::table('page_contents', function (Blueprint $table) {
                $table->dropColumn(['section', 'key', 'value', 'type', 'sort_order']);
            });
        }
        
        // Add new columns
        Schema::table('page_contents', function (Blueprint $table) {
            $table->string('title')->nullable();
            $table->longText('content')->nullable();
            $table->string('content_type')->default('text');
            $table->boolean('is_published')->default(true);
            $table->integer('order')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('page_contents', function (Blueprint $table) {
            $table->dropColumn(['title', 'content', 'content_type', 'is_published', 'order']);
        });
        
        Schema::table('page_contents', function (Blueprint $table) {
            $table->string('section')->index();
            $table->string('key')->index();
            $table->longText('value');
            $table->string('type')->default('text');
            $table->integer('sort_order')->default(0);
        });
    }
};
