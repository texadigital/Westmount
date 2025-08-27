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
        Schema::create('funds', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nom du fonds
            $table->string('description');
            $table->decimal('current_balance', 12, 2)->default(0); // Solde actuel
            $table->decimal('total_contributions', 12, 2)->default(0); // Total des contributions
            $table->decimal('total_distributions', 12, 2)->default(0); // Total des distributions
            $table->enum('type', ['general', 'death_benefit', 'emergency'])->default('general');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['type', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('funds');
    }
};
