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
        Schema::table('members', function (Blueprint $table) {
            $table->string('lapsed_code')->nullable()->after('sponsor_id');
            $table->timestamp('reactivated_at')->nullable()->after('lapsed_code');
            $table->string('reactivation_code')->nullable()->after('reactivated_at');
        });

        Schema::create('member_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->string('type'); // 'sponsorship_used', 'payment_confirmed', 'membership_expired', etc.
            $table->string('title');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->json('metadata')->nullable(); // Additional data
            $table->timestamps();
            
            $table->index(['member_id', 'is_read']);
            $table->index(['type', 'sent_at']);
        });

        Schema::create('lapsed_member_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->string('code')->unique();
            $table->timestamp('expires_at');
            $table->boolean('is_used')->default(false);
            $table->timestamp('used_at')->nullable();
            $table->timestamps();
            
            $table->index(['code', 'is_used']);
            $table->index(['expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lapsed_member_codes');
        Schema::dropIfExists('member_notifications');
        
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn(['lapsed_code', 'reactivated_at', 'reactivation_code']);
        });
    }
};
