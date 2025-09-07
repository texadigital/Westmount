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
        Schema::table('page_contents', function (Blueprint $table) {
            // Drop the old columns
            $table->dropColumn(['section', 'key', 'value', 'type', 'sort_order']);
            
            // Add the new columns that match the model
            $table->string('title')->after('page');
            $table->longText('content')->after('title');
            $table->string('meta_title')->nullable()->after('content');
            $table->text('meta_description')->nullable()->after('meta_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('page_contents', function (Blueprint $table) {
            // Drop the new columns
            $table->dropColumn(['title', 'content', 'meta_title', 'meta_description']);
            
            // Add back the old columns
            $table->string('section')->index()->after('page');
            $table->string('key')->index()->after('section');
            $table->longText('value')->after('key');
            $table->string('type')->default('text')->after('value');
            $table->integer('sort_order')->default(0)->after('is_active');
        });
    }
};