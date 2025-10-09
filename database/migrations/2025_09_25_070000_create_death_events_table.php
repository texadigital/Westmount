<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('death_events', function (Blueprint $table) {
            $table->id();
            $table->string('deceased_name');
            $table->date('date_of_death')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->string('status')->default('draft'); // draft|published|closed
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('death_events');
    }
};
