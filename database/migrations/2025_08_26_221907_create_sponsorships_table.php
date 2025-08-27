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
        Schema::create('sponsorships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sponsor_id')->constrained('members')->onDelete('cascade'); // Parrain
            $table->string('sponsorship_code')->unique(); // Code de parrainage unique
            $table->string('prospect_first_name');
            $table->string('prospect_last_name');
            $table->string('prospect_email');
            $table->string('prospect_phone');
            $table->enum('status', ['pending', 'confirmed', 'completed', 'expired'])->default('pending');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['sponsor_id', 'status']);
            $table->index(['sponsorship_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sponsorships');
    }
};
