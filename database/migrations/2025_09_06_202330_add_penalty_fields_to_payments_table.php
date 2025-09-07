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
        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('penalty_amount', 10, 2)->default(0)->after('amount');
            $table->decimal('total_amount', 10, 2)->default(0)->after('penalty_amount');
            $table->integer('overdue_days')->default(0)->after('total_amount');
            $table->boolean('penalty_applied')->default(false)->after('overdue_days');
            $table->timestamp('penalty_applied_at')->nullable()->after('penalty_applied');
            $table->text('penalty_reason')->nullable()->after('penalty_applied_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'penalty_amount',
                'total_amount',
                'overdue_days',
                'penalty_applied',
                'penalty_applied_at',
                'penalty_reason',
            ]);
        });
    }
};