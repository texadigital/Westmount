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
        Schema::create('penalty_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->decimal('penalty_amount', 10, 2)->default(0);
            $table->integer('penalty_type')->default(1); // 1: fixed, 2: percentage
            $table->integer('grace_period_days')->default(30);
            $table->integer('escalation_days')->default(60);
            $table->decimal('escalation_multiplier', 3, 2)->default(1.5);
            $table->boolean('is_active')->default(true);
            $table->json('notification_schedule')->nullable(); // [7, 14, 30, 60] days
            $table->timestamps();
        });

        // Insert default penalty settings
        DB::table('penalty_settings')->insert([
            [
                'name' => 'default_adhesion_penalty',
                'description' => 'Pénalité par défaut pour les paiements d\'adhésion en retard',
                'penalty_amount' => 5.00,
                'penalty_type' => 1, // fixed
                'grace_period_days' => 30,
                'escalation_days' => 60,
                'escalation_multiplier' => 1.5,
                'is_active' => true,
                'notification_schedule' => json_encode([7, 14, 30, 60]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'default_contribution_penalty',
                'description' => 'Pénalité par défaut pour les contributions en retard',
                'penalty_amount' => 2.00,
                'penalty_type' => 1, // fixed
                'grace_period_days' => 30,
                'escalation_days' => 60,
                'escalation_multiplier' => 1.5,
                'is_active' => true,
                'notification_schedule' => json_encode([7, 14, 30, 60]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penalty_settings');
    }
};