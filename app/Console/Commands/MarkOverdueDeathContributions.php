<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DeathContribution;

class MarkOverdueDeathContributions extends Command
{
    protected $signature = 'death-contributions:mark-overdue';
    protected $description = 'Mark death contributions as overdue when past due date, and keep paid as-is';

    public function handle(): int
    {
        $now = now()->startOfDay();

        $updated = DeathContribution::query()
            ->where('status', 'pending')
            ->whereDate('due_date', '<', $now)
            ->update(['status' => 'overdue']);

        $this->info("Updated {$updated} contributions to overdue.");
        return self::SUCCESS;
    }
}
