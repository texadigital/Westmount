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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('business_number')->unique(); // Numéro d'entreprise
            $table->string('contact_person');
            $table->string('contact_email');
            $table->string('contact_phone');
            $table->text('address');
            $table->string('city');
            $table->string('province');
            $table->string('postal_code');
            $table->string('country')->default('Canada');
            $table->integer('member_count')->default(0); // Nombre de membres
            $table->decimal('total_fees', 10, 2)->default(0); // Total des frais
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['business_number']);
            $table->index(['is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
