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
        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['active', 'overdue', 'lapsed'])->default('active'); // actif, en retard, caduc
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('adhesion_fee_paid', 8, 2)->default(0);
            $table->decimal('total_contributions_paid', 8, 2)->default(0);
            $table->decimal('amount_due', 8, 2)->default(0); // Montant dû
            $table->date('last_payment_date')->nullable();
            $table->date('next_payment_due')->nullable();
            $table->integer('overdue_days')->default(0);
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['member_id', 'status']);
            $table->index(['status', 'next_payment_due']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memberships');
    }
};
