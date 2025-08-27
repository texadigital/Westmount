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
            // Ajouter les clés étrangères après que toutes les tables soient créées
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('set null');
            $table->foreign('sponsor_id')->references('id')->on('members')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
            $table->dropForeign(['sponsor_id']);
        });
    }
};
