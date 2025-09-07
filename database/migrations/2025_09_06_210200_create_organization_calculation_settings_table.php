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
        Schema::create('organization_calculation_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->decimal('adhesion_fee_per_member', 8, 2);
            $table->string('adhesion_fee_formula')->default('adhesion_fee_per_member * member_count');
            $table->string('contribution_fee_formula')->default('sum_of_individual_contributions');
            $table->boolean('include_penalties')->default(false);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->integer('min_members_for_discount')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_calculation_settings');
    }
};
