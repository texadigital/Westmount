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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membership_id')->constrained()->onDelete('cascade');
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['adhesion', 'contribution', 'penalty', 'renewal']); // Type de paiement
            $table->decimal('amount', 8, 2);
            $table->string('currency')->default('CAD');
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->string('stripe_payment_intent_id')->nullable(); // ID Stripe
            $table->string('stripe_charge_id')->nullable(); // ID de charge Stripe
            $table->string('payment_method')->default('stripe'); // Méthode de paiement
            $table->text('description')->nullable();
            $table->json('metadata')->nullable(); // Données supplémentaires
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            
            $table->index(['member_id', 'status']);
            $table->index(['membership_id', 'type']);
            $table->index(['stripe_payment_intent_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
