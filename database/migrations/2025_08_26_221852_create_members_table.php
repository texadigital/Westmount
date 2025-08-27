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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('member_number')->unique(); // Numéro de membre unique
            $table->string('pin_code'); // Code PIN chiffré
            $table->string('first_name');
            $table->string('last_name');
            $table->date('birth_date');
            $table->string('phone');
            $table->string('email')->unique();
            $table->text('address');
            $table->string('city');
            $table->string('province');
            $table->string('postal_code');
            $table->string('country')->default('Canada');
            $table->string('canadian_status_proof'); // Preuve de statut au Canada
            $table->foreignId('member_type_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('organization_id')->nullable(); // Clé étrangère ajoutée plus tard
            $table->unsignedBigInteger('sponsor_id')->nullable(); // Clé étrangère ajoutée plus tard
            $table->boolean('is_active')->default(true);
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->timestamps();
            
            $table->index(['member_number', 'pin_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
