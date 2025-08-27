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
        Schema::create('member_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // régulier, senior, junior, association
            $table->string('description');
            $table->decimal('adhesion_fee', 8, 2); // Frais d'adhésion
            $table->decimal('death_contribution', 8, 2); // Contribution décès
            $table->integer('min_age')->nullable(); // Âge minimum
            $table->integer('max_age')->nullable(); // Âge maximum
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_types');
    }
};
