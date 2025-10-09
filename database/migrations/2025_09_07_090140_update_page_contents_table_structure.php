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
        // Drop legacy columns if they exist
        Schema::table('page_contents', function (Blueprint $table) {
            $columnsToDrop = [];
            foreach (['section', 'key', 'value', 'type', 'sort_order', 'content_type', 'is_published', 'order'] as $col) {
                if (Schema::hasColumn('page_contents', $col)) {
                    $columnsToDrop[] = $col;
                }
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });

        // Add expected columns only if missing
        Schema::table('page_contents', function (Blueprint $table) {
            if (!Schema::hasColumn('page_contents', 'title')) {
                $table->string('title')->nullable();
            }
            if (!Schema::hasColumn('page_contents', 'content')) {
                $table->longText('content')->nullable();
            }
            if (!Schema::hasColumn('page_contents', 'meta_title')) {
                $table->string('meta_title')->nullable();
            }
            if (!Schema::hasColumn('page_contents', 'meta_description')) {
                $table->text('meta_description')->nullable();
            }
            if (!Schema::hasColumn('page_contents', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('page_contents', function (Blueprint $table) {
            $columnsToDrop = [];
            foreach (['title', 'content', 'meta_title', 'meta_description', 'is_active'] as $col) {
                if (Schema::hasColumn('page_contents', $col)) {
                    $columnsToDrop[] = $col;
                }
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });

        // Optionally restore legacy columns
        Schema::table('page_contents', function (Blueprint $table) {
            if (!Schema::hasColumn('page_contents', 'section')) {
                $table->string('section')->index();
            }
            if (!Schema::hasColumn('page_contents', 'key')) {
                $table->string('key')->index();
            }
            if (!Schema::hasColumn('page_contents', 'value')) {
                $table->longText('value');
            }
            if (!Schema::hasColumn('page_contents', 'type')) {
                $table->string('type')->default('text');
            }
            if (!Schema::hasColumn('page_contents', 'sort_order')) {
                $table->integer('sort_order')->default(0);
            }
        });
    }
};

