<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('death_contributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('death_event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('CAD');
            $table->date('due_date');
            $table->string('status')->default('pending'); // pending|paid|overdue
            $table->timestamp('paid_at')->nullable();
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('death_contributions');
    }
};
